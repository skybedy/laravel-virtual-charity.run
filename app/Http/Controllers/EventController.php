<?php

namespace App\Http\Controllers;

use App\Exceptions\DuplicateFileException;
use App\Exceptions\SmallDistanceException;
use App\Exceptions\TimeIsOutOfRangeException;
use App\Exceptions\TimeMissingException;
use App\Exceptions\DuplicityTimeException;
use App\Exceptions\NoStravaAuthorizeException;
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

    public function uploadUrlCreate(Request $request, Event $event)
    {
        return view('events.results.upload-url-create', [
            'event' => $event::find($request->eventId),
        ]);
    }

    public function uploadFileCreate(Request $request, Event $event)
    {
        return view('events.results.upload-file-create', [
            'event' => $event::find($request->eventId),
        ]);
    }



    public function uploadStoreFromUrl(Request $request, ResultService $resultService, Registration $registration, TrackPoint $trackPoint, Event $event)
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

        try{
            $activityData = $resultService->getStreamFromStrava($request, $activityId);
        }
        catch (NoStravaAuthorizeException $e)
        {
            return back()->withError($e->getMessage())->withInput();
        }

        try
        {
            $finishTime = $this->activityFinishTime($resultService,'getActivityFinishDataFromStravaStream',['activity_data' => $activityData,'request' => $request]);
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

        try
        {
            $resultSave = $resultService->resultSave($request, $registrationId, $finishTime);
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



    private function activityFinishTime($resultService,$methodName,$args)
    {
        //try
        //{

            if (method_exists($resultService, $methodName))
            {
                $finishTime = call_user_func([$resultService, $methodName], $args);
            }
            else
            {
                throw new Exception("Metoda neexistuje");
            }


       /* }
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
        }*/

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
            $finishTime = $this->activityFinishTime($resultService,'getActivityFinishDataFromGpx',['request' => $request]);
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
