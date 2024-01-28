<?php

namespace App\Http\Controllers;

use App\Exceptions\DuplicateFileException;
use App\Exceptions\SmallDistanceException;
use App\Exceptions\TimeIsOutOfRangeException;
use App\Exceptions\TimeMissingException;
use App\Exceptions\DuplicityTimeException;
use Exception;
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

        if (isset($registration->registrationExists($request->eventId, $request->user()->id)->id))
        {
            $registrationId = $registration->registrationExists($request->eventId, $request->user()->id)->id;
        }
        else
        {
            return back()->withError('registration_required')->withInput();
        }







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

        $this->test($request, $activityId, $resultService, $registration);

    }










    private function test($request,$activityId, $resultService,$registration)
    {






        $user = User::select('id', 'strava_access_token', 'strava_refresh_token', 'strava_expires_at')->where('id',$request->user()->id,)->first();

        if ($user->strava_expires_at > time()) {

            $urlStream = config('strava.stream.url').$activityId.config('strava.stream.params');

            $token = $user->strava_access_token;

            $response = Http::withToken($token)->get($urlStream)->json();

            if ($response) {

                $urlActivity = config('strava.activity.url').$activityId.config('strava.activity.params');

                $response += Http::withToken($token)->get($urlActivity)->json();

               // $finishTime = $resultService->dataFromStravaStream($response, $registration, $userId);
                $finishTime = $this->activityFinishTime($resultService,'dataFromStravaStream',$request);

                dd($finishTime);

            }

        }
        else //TOKEN EXPIRED
        {   // URL na Stravu na vymenu tokenu
            $urlToken = config('strava.token.url');
            // parametry pro vymenu tokenu
            $params = config('strava.token.params');
            // doplneni parametru o refresh token
            $params['refresh_token'] = $user->strava_refresh_token;

            $responseToken = Http::post($urlToken,$params);

            $body = $responseToken->body();

            $content = json_decode($body, true);

            $user = new User();

            $token = $user->updateStravaToken($request->user()->id,$content);

            $urlStream = config('strava.stream.url').$activityId.config('strava.stream.params');

            $response = Http::withToken($token)->get($urlStream)->json();

            if ($response) {

                $urlActivity = config('strava.activity.url').$activityId.config('strava.activity.params');

                $response += Http::withToken($token)->get($urlActivity)->json();

                dd($response);

                //$data = $this->dataProcessing($resultService,$registration,$trackPoint,$event,$response,$user->id);
            }
        }

    }





    private function activityFinishTime($resultService,$methodName,$request)
    {
        try
        {
            //$finishTime = $resultService->activityFinishData($request);

            if (method_exists($resultService, $methodName))
            {
                $finishTime = call_user_func_array([$resultService, $methodName], [$request]);
            }
            else
            {
                throw new Exception("Metoda neexistuje");
            }


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
        //


        try
        {
            $finishTime = $this->activityFinishTime($resultService,'activityFinishData',$request);
        }
        catch(Exception $e)
        {
            return back()->withError($e->getMessage());
        }


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
