<?php

namespace App\Http\Controllers;

use App\Exceptions\DuplicateFileException;
use App\Exceptions\SmallDistanceException;
use App\Exceptions\TimeIsOutOfRangeException;
use App\Exceptions\TimeMissingException;
use App\Exceptions\DuplicityTimeException;
use App\Models\Event;
use App\Models\Registration;
use App\Models\Result;
use App\Models\Startlist;
use App\Models\TrackPoint;
use App\Models\User;
use App\Services\ResultService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Event $event)
    {
        if ($request->user() == null) {
            return view('events.index', [
                'events' => $event::All(),
            ]);
        } else {
            return view('events.index', [
                'events' => $event->eventList($request->user()->id),
            ]);
        }

    }

    public function show(Request $request, Event $event)
    {

        return view('events.show', [
            'event' => $event::find($request->eventId),
        ]);
    }

    public function uploadCreate(Request $request, Event $event)
    {
        return view('events.results.upload-create', [
            'event' => $event::find($request->eventId),
        ]);
    }




    public function uploadStoreUrl(Request $request, ResultService $resultService, Registration $registration, TrackPoint $trackPoint, Event $event)
    {
        //nejprve validace
        $request->validate(
            [
                'strava_url' => 'required',
            ],
            [
                'strava_url.required' => 'Je nutné vyplnit odkaz na Stravy.',
            ]
        );
        //urceni, zda jde o link z prohlizece nebo z apky
        $subdomain = $resultService->getSubdomain($request['strava_url']);
        // ziskani id aktivity
        if ($subdomain == 'www')
        {
            $activityId = $resultService->getActivityId($request['strava_url']);
        }
        elseif ($subdomain == 'strava')
        {
            $activityId = $resultService->getActivityIdFromStravaShareLink($request['strava_url']);
        }
        else
        {
            //'nejaky problem s url');
        }

        $this->test($request->user()->id, $activityId);

    }










    private function test($userId, $activityId)
    {

        $user = User::select('id', 'strava_access_token', 'strava_refresh_token', 'strava_expires_at')->where('id', $userId)->first();

        if ($user->strava_expires_at > time()) {

            //$url = "https://www.strava.com/api/v3/activities/".$request->input('object_id')."?include_all_efforts=true";

            $url = 'https://www.strava.com/api/v3/activities/'.$activityId.'/streams?keys=time,latlng,altitude&key_by_type=true';

            $token = $user->strava_access_token;
            $response = Http::withToken($token)->get($url)->json();

            if ($response) {
                $url = 'https://www.strava.com/api/v3/activities/'.$activityId.'?include_all_efforts=false';
                $response += Http::withToken($token)->get($url)->json();
                dd($response);

                //$data = $this->dataProcessing($resultService,$registration,$trackPoint,$event,$response,$user->id);
            }

        }
        else
        {
            $url = config('strava.token.url');

            $params = config('strava.token.params');

            $params['refresh_token'] = $user->strava_refresh_token;

            $response = Http::post($url,$params);

            $body = $response->body();

            $content = json_decode($body, true);

            $user1 = User::where('id', $user->id)->first();

            $user1->strava_access_token = $content['access_token'];

            $user1->strava_refresh_token = $content['refresh_token'];

            $user1->strava_expires_at = $content['expires_at'];

            $user1->save();

            $url = 'https://www.strava.com/api/v3/activities/'.$activityId.'?include_all_efforts=true';
            $token = $user->strava_access_token;
            $response = Http::withToken($token)->get($url);
            $data = $content;

            $data = $user1;
            $data .= 'tady jsem';

        }

    }





    private function activityFinishTime($resultService,$request)
    {
        try
        {
            $finishTime = $resultService->activityFinishData($request);
        }
        catch (SmallDistanceException $e)
        {
            return back()->withError($e->getMessage())->withInput();
        }
        catch (TimeIsOutOfRangeException $e)
        {
            return back()->withError($e->getMessage())->withInput();
        }
        catch (DuplicateFileException $e)
        {
            return back()->withError($e->getMessage())->withInput();
        }
        catch (TimeMissingException $e)
        {
            return back()->withError($e->getMessage())->withInput();
        }
        catch (UniqueConstraintViolationException $e)
        {
            $errorCode = $e->errorInfo[1];

            if ($errorCode == 1062)
            {
                // Duplicitní záznam byl nalezen, zde můžete provést potřebné akce
                // Například můžete záznam přeskočit, aktualizovat nebo vrátit chybovou zprávu uživateli
            }
        }

        return $finishTime;

    }





    public function uploadStore(Request $request, ResultService $resultService, Registration $registration, TrackPoint $trackPoint, Event $event)
    {

        $request->validate(
            [
                //'gpx_file' => 'required|mimetypes:application/gpx+xml|max:10000',
                'gpx_file' => 'required|max:10000',
            ],
            [
                'gpx_file.required' => 'Nebyl vybrán žádný soubor.',
            ]);
        // kontrola, zda uzivatel je registrovan na zavod
        if (isset($registration->registrationExists($request->eventId, $request->user()->id)->id))
        {
            $registration_id = $registration->registrationExists($request->eventId, $request->user()->id)->id;
        }
        else
        {
            return back()->withError('registration_required')->withInput();
        }


        $finishTime = $this->activityFinishTime($resultService,$request);


        try
        {
            $resultSave = $resultService->resultSave($request, $registration_id, $finishTime);
        }
        catch (DuplicityTimeException $e)
        {
            return back()->withError($e->getMessage())->withInput();
        }





        if (isset($resultSave['error']))
        {
            if ($resultSave['error'] == 'ERROR_DB')
            {
                return back()->withError('Došlo k problému s nahráním souboru, kontaktujte timechip.cz@gmail.com')->withInput();
            }
        }





        return view('events.results.post-upload', [
            'results' => $resultSave['results'],
            'event' => $resultSave['event'],
            'last_id' => $resultSave['last_id'],
            'rank' => $resultSave['rank'],
        ]);

    }


    public function resultIndex(Request $request, Result $result, Event $event)
    {
        return view('events.results.result-index', [
            'results' => $result->resultsOverall($request->eventId),
            'event' => $event::find($request->eventId),
        ]);
    }

    public function startlistIndex(Request $request, Registration $registration, Event $event, Startlist $startlist)
    {
        return view('events.start-list', [
            'startlists' => $startlist->startlist($request->eventId),
            'event' => $event::find($request->eventId),
        ]);
    }
}
