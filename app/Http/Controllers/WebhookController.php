<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\ResultService;
use App\Models\Registration;
use App\Models\TrackPoint;
use App\Models\Event;
use App\Models\Result;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;




class WebhookController extends Controller
{


    public function autouploadStrava(ResultService $resultService,Registration $registration,TrackPoint $trackPoint,Event $event)
    {

        $url = "https://www.strava.com/api/v3/activities/10503391125?include_all_efforts=true";
        $token = "f2c183e83f7e1528e6a135af1520c928cc304b00";
        $response = Http::withToken($token)->get($url);
        $data = $response->json();
        $finishTime = $resultService->dataStravaProcessing($response->json(),$registration);

        $result = new Result();
        $result->registration_id = $finishTime['registration_id'];
        $result->finish_time_date = $finishTime['finish_time_date'];
        $result->finish_time = $finishTime['finish_time'];
        $result->average_time_per_km = $finishTime['average_time_per_km'];
        $result->finish_time_sec = $finishTime['finish_time_sec'];
       // $result->duplicity_check = $finishTime['duplicity_check'];
        $result->place = "Nevim";

       // dd($result);

        DB::beginTransaction();

        try{
            $result->save();
        }
        catch(QueryException $e)

        {
            dd($e);
            return back()->withError('Došlo k problému s nahráním souboru, kontaktujte timechip.cz@gmail.com')->withInput();;
        }


        for($i = 0; $i < count($finishTime['track_points']); $i++)
        {
            $finishTime['track_points'][$i]['result_id'] = $result->id;
        }


       // $trackPoint::insert($finishTime['track_points']);

        try{
            $trackPoint::insert($finishTime['track_points']);
            DB::commit();
        }
        catch (UniqueConstraintViolationException $e)
        {

         dd($e);
         if($e->errorInfo[1] == 1062)
            {
                DB::rollback();
                return back()->withError('Soubor obsahuje duplicitní časové údaje')->withInput();
            }
        }

        $r = Result::where('registration_id', $finishTime['registration_id'])
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





    }



    public function getStrava(Request $request)
    {

        \Log::info($request->query());
        $VERIFY_TOKEN = "STRAVA";

        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

//        if ($mode && $token) {
            if ($mode === 'subscribe' && $token === $VERIFY_TOKEN) {
                \Log::info('WEBHOOK_VERIFIED');
                return response()->json(['hub.challenge' => $challenge]);
            } else {

                \Log::info('neco-spatne');
                return response('Forbidden', 403);
            }
       // }
         //   else{
           //     \Log::info('neco-spatne-tu');
            //}
    }

    public function postStrava(Request $request)
    {



        \Log::info("Webhook event received!", [
            'query' => $request->query(),
            'body' => $request->all()
        ]);

        return response('EVENT_RECEIVED', 200);
    }





    /*

{"token_type":"Bearer","access_token":"f2c183e83f7e1528e6a135af1520c928cc304b00","expires_at":1704744984,"expires_in":21600,"refresh_token":"5a0de4bd8330b7b31e58c1895fd49d44ce111fc0"}skybedy@skybedy-Latitude-E6520:~$ ^C
*/



}
