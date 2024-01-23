<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Registration;
use App\Models\Result;
use App\Models\Startlist;
use App\Models\User;
use App\Services\ResultService;
use App\Exceptions\SmallDistanceException;
use App\Exceptions\TimeIsOutOfRangeException;
use App\Exceptions\TimeMissingException;
use App\Exceptions\DuplicateFileException;
use App\Models\TrackPoint;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;




class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Event $event)
    {
        if($request->user() == null)
        {
            return view('events.index', [
                'events' => $event::All(),
            ]);
        }
        else
        {
            return view('events.index', [
                'events' => $event->eventList($request->user()->id),
            ]);
        }

    }

    public function show(Request $request,Event $event)
    {

        return view('events.show', [
            'event' => $event::find($request->eventId),
        ]);
    }

    public function uploadCreate(Request $request,Event $event)
    {
        return view('events.results.upload-create', [
            'event' => $event::find($request->eventId),
        ]);
    }


    private function url


    public function uploadStoreUrl(Request $request, ResultService $resultService,Registration $registration,TrackPoint $trackPoint,Event $event)
    {

        $request->validate(
            [
                'strava_url' => 'required',
            ],
            [
                'strava_url.required' => 'Je nutné vyplnit odkaz na Stravy.',
            ]);


            $parseUrl = parse_url($request['strava_url']);
            $explodeHost = explode('.', $parseUrl['host']);
            $word = $explodeHost[0];
            if($word ==  'www')
            {
                $host = $explodeHost[1];
            }
            elseif($word ==  'strava')
            {
                $host = $explodeHost[0];
            }
            else
            {
                //'nejaky problem s url');
            }







            $container = [];
            $history = Middleware::history($container);

            $stack = HandlerStack::create();
            $stack->push($history);

            $client = new Client([
                'handler' => $stack,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
                ]
            ]);

            $response = $client->get('https://strava.app.link/BtqOkiqlzGb');

            $finalUrl = '';
            foreach ($container as $transaction) {
                $finalUrl = (string)$transaction['request']->getUri();
            }

            if (preg_match('/\/activities\/(\d+)/', $finalUrl, $matches)) {
                $activityId = $matches[1];
                dd("Activity ID: $activityId");
            } else {
                dd("No activity ID found in URL");
            }
           // dd();



















            $url = $request['strava_url'];
        $lastChar = substr($url, -1);
        if($lastChar == '/')
        {
            $url = substr($url, 0, -1);
        }


        $activityId = substr($url, strrpos($url, '/') + 1);

        $this->test($request->user()->id,$activityId);

    }


    private function test($userId,$activityId)
    {


        $user = User::select('id','strava_access_token','strava_refresh_token','strava_expires_at')->where('id',$userId)->first();


        if($user->strava_expires_at > time())
        {

            //$url = "https://www.strava.com/api/v3/activities/".$request->input('object_id')."?include_all_efforts=true";

            $url = "https://www.strava.com/api/v3/activities/".$activityId."/streams?keys=time,latlng,altitude&key_by_type=true";




            $token = $user->strava_access_token;
            $response = Http::withToken($token)->get($url)->json();



            if($response)
            {
                $url = "https://www.strava.com/api/v3/activities/".$activityId."?include_all_efforts=false";
                $response += Http::withToken($token)->get($url)->json();
                dd($response);

                //$data = $this->dataProcessing($resultService,$registration,$trackPoint,$event,$response,$user->id);
            }

        }
        else
        {
            $response = Http::post('https://www.strava.com/oauth/token', [
                'client_id' => '117954',
                'client_secret' => 'a56df3b8bb06067ebe76c7d23af8ee8211d11381',
                'refresh_token' => $user->strava_refresh_token,
                'grant_type' => 'refresh_token',
            ]);

            $body = $response->body();
            $content = json_decode($body, true);


            $user1 = User::where('id',$user->id)->first();
            $user1->strava_access_token = $content['access_token'];
            $user1->strava_refresh_token = $content['refresh_token'];
            $user1->strava_expires_at = $content['expires_at'];


            $user1->save();



            $url = "https://www.strava.com/api/v3/activities/".$activityId."?include_all_efforts=true";
            $token = $user->strava_access_token;
            $response = Http::withToken($token)->get($url);
            $data = $content;

            $data = $user1;
            $data .= "tady jsem";



        }

    }








    public function uploadStore(Request $request, ResultService $resultService,Registration $registration,TrackPoint $trackPoint,Event $event)
    {


        $request->validate(
        [
            'gpx_file' => 'required|mimetypes:application/xml,application/octet-stream|max:10000',
        ],
        [
            'gpx_file.required' => 'Nebyl vybrán žádný soubor.',
        ]);



        if(isset($registration->registrationExists( $request->eventId, $request->user()->id)->id))
        {
            $registration_id = $registration->registrationExists( $request->eventId, $request->user()->id)->id;
        }
        else
        {
            return back()->withError('registration_required')->withInput();
        }







        try
        {
            $finishTime = $resultService->finishTime($request);
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
        catch (UniqueConstraintViolationException $e) {
            //dd('tu');
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062){
                // Duplicitní záznam byl nalezen, zde můžete provést potřebné akce
                // Například můžete záznam přeskočit, aktualizovat nebo vrátit chybovou zprávu uživateli
            }
        }

        $result = new Result();
        $result->registration_id = $registration_id;
        $result->finish_time_date = $finishTime['finish_time_date'];
        $result->finish_time = $finishTime['finish_time'];
        $result->average_time_per_km = $finishTime['average_time_per_km'];
        $result->finish_time_sec = $finishTime['finish_time_sec'];
        $result->duplicity_check = $finishTime['duplicity_check'];
        $result->place = $request->place;

        DB::beginTransaction();


        try{
            $result->save();
        }
        catch(QueryException $e)

        {
            return back()->withError('Došlo k problému s nahráním souboru, kontaktujte timechip.cz@gmail.com')->withInput();;
        }


        for($i = 0; $i < count($finishTime['track_points']); $i++)
        {
            $finishTime['track_points'][$i]['result_id'] = $result->id;
        }




        try{
            $trackPoint::insert($finishTime['track_points']);
            DB::commit();
        }
        catch (UniqueConstraintViolationException $e)
        {
            if($e->errorInfo[1] == 1062)
            {
                DB::rollback();
                return back()->withError('Soubor obsahuje duplicitní časové údaje')->withInput();
            }
        }

        $r = Result::where('registration_id', $registration_id)
        ->orderBy('finish_time', 'asc')
        ->get();




        $lastId = $result->id;
        foreach($r as $key => $value)
        {
            if($value->id == $lastId)
            {
                $rank = $key + 1;
            }

            Result::where('id', $value->id)->update(['finish_time_order' => $key + 1]);
        }


/*
if($rank == 1)
{
    dd("Je to zatím tvůj nejlepší čas v tomto závodě a bude zařazen do celkových výsledků");
}
else
{
   // dd("Je to celkově tvůj $rank.čas v tomto závodě a do celkových výsledků se započítávat nebude, seznam všech tvých časů je v tabulce");
    return view('events.results.post-upload', [
        'results' =>  Result::where('registration_id', $registration_id)
        ->orderBy('finish_time_sec', 'asc')
        ->get(),
    ]);

}*/


return view('events.results.post-upload', [
    'results' =>  Result::selectRaw('id,DATE_FORMAT(finish_time_date,"%e.%c") AS date,place,finish_time')
    ->where('registration_id', $registration_id)
    ->orderBy('finish_time', 'asc')
    ->get(),
    'event' => $event::find($request->eventId),
    'last_id' => $lastId,
    'rank' => $rank
]);






    }



    public function resultIndex(Request $request,Result $result,Event $event)
    {
        return view('events.results.result-index', [
            'results' =>  $result->resultsOverall($request->eventId),
            'event' => $event::find($request->eventId),
        ]);
    }

    public function startlistIndex(Request $request,Registration $registration,Event $event,Startlist $startlist)
    {
        return view('events.start-list', [
            'startlists' => $startlist->startlist($request->eventId),
            'event' => $event::find($request->eventId),
        ]);
    }

}
