<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\Result;
use App\Models\TrackPoint;
use App\Models\User;
use App\Services\ResultService;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StravaController extends Controller
{
    //  public function dataProcessing(ResultService $resultService,Registration $registration,TrackPoint $trackPoint,Event $event)

    public function dataProcessing($resultService, $registration, $trackPoint, $event, $dataStream, $userId)
    {

        //return $dataStream;

        $finishTime = $resultService->dataFromStravaStream($dataStream, $registration, $userId);

        $result = new Result();
        $result->registration_id = $finishTime['registration_id'];
        $result->finish_time_date = $finishTime['finish_time_date'];
        $result->finish_time = $finishTime['finish_time'];
        $result->average_time_per_km = $finishTime['average_time_per_km'];
        $result->finish_time_sec = $finishTime['finish_time_sec'];
        // $result->duplicity_check = $finishTime['duplicity_check'];
        $result->place = 'Nevim';

        // dd($result);

        DB::beginTransaction();

        try {
            $result->save();
        } catch (QueryException $e) {
            dd($e);

            return back()->withError('Došlo k problému s nahráním souboru, kontaktujte timechip.cz@gmail.com')->withInput();
        }

        for ($i = 0; $i < count($finishTime['track_points']); $i++) {
            $finishTime['track_points'][$i]['result_id'] = $result->id;
        }

       // $trackPoint::insert($finishTime['track_points']);

        try {
            $trackPoint::insert($finishTime['track_points']);
            DB::commit();
        } catch (UniqueConstraintViolationException $e) {

            dd($e);
            if ($e->errorInfo[1] == 1062) {
                DB::rollback();

                return back()->withError('Soubor obsahuje duplicitní časové údaje')->withInput();
            }
        }

        $r = Result::where('registration_id', $finishTime['registration_id'])
            ->orderBy('finish_time', 'asc')
            ->get();

        $lastId = $result->id;
        foreach ($r as $key => $value) {
            if ($value->id == $lastId) {
                $rank = $key + 1;
            }

            Result::where('id', $value->id)->update(['finish_time_order' => $key + 1]);
        }

    }

    public function getStrava(Request $request)
    {

        \Log::info($request->query());
        $VERIFY_TOKEN = 'STRAVA';

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




    /**
     *   zpracování webhook  ze Stravy
    */
   public function webhookPostStrava(Request $request, ResultService $resultService, Registration $registration, TrackPoint $trackPoint, Event $event)
    {

        // zaloguje se prijem dat ze Stravy
        Log::info('Webhook event received!', [
            'query' => $request->query(),
            'body' => $request->all(),
        ]);
        //pokud to neni 'create'tak to nechcem
        if ($request->input('aspect_type') != 'create') {
            return;
        }
        //z pozadavku si vezmeme id uzivatele Stravy a podle nej najdeme uzivatele v nasi databazi
        $stravaId = $request->input('owner_id');

        $user = User::select('id', 'strava_access_token', 'strava_refresh_token', 'strava_expires_at')->where('strava_id', $stravaId)->first();
        //ted musime zjistit, jestli token pro REST API jeste plati
        if ($user->strava_expires_at > time())
        {
            //pokud token platí, vytahneme stream
            $url = config('strava.stream.url').$request->input('object_id').config('strava.stream.params');

            $token = $user->strava_access_token;

            $response = Http::withToken($token)->get($url)->json();
            //pokud dostaneme v poradku stream, tak vytahneme i detail aktivity
            if ($response)
            {
                $url = config('strava.activity.url').$request->input('object_id').config('strava.activity.params');
                // k predchozimu streamu pridame detail aktivity
                $response += Http::withToken($token)->get($url)->json();

                $data = $this->dataProcessing($resultService, $registration, $trackPoint, $event, $response, $user->id);
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

            $user1 = User::where('id', $user->id)->first();
            $user1->strava_access_token = $content['access_token'];
            $user1->strava_refresh_token = $content['refresh_token'];
            $user1->strava_expires_at = $content['expires_at'];

            $user1->save();

            $url = 'https://www.strava.com/api/v3/activities/'.$request->input('object_id').'?include_all_efforts=true';
            $token = $user->strava_access_token;
            $response = Http::withToken($token)->get($url);
            $data = $content;

            $data = $user1;
            $data .= 'tady jsem';

        }

        return response($data, 200);
    }

    private function getUserByStravaId($stravaId)
    {
        return User::select('id', 'strava_access_token', 'strava_refresh_token', 'strava_expires_at')->where('strava_id', $stravaId)->first();
    }

    public function redirectStrava(Request $request)
    {

        $response = Http::post('https://www.strava.com/oauth/token', [
            'client_id' => '117954',
            'client_secret' => 'a56df3b8bb06067ebe76c7d23af8ee8211d11381',
            'code' => $request->query('code'),
            'grant_type' => 'authorization_code',
        ]);

        $body = $response->body();
        $content = json_decode($body, true);
        //dd($content);

        $user = User::find($request->userId);
        $user->strava_id = $content['athlete']['id'];
        $user->strava_access_token = $content['access_token'];
        $user->strava_refresh_token = $content['refresh_token'];
        $user->strava_expires_at = $content['expires_at'];
        $user->strava_scope = $request->query('scope');
        $user->save();

        return redirect('/');

    }

    //simulace autonahrani ze Stravy
    public function autouploadStrava(ResultService $resultService, Registration $registration, TrackPoint $trackPoint, Event $event)
    {

        $url = 'https://www.strava.com/api/v3/activities/10531467027/streams?keys=time,latlng,altitude&key_by_type=true';
        $token = '5a525bfc7d1210250d6727f701cb91db34d19300';
        $response = Http::withToken($token)->get($url)->json();
        //dd($response);
        if ($response) {
            $url = 'https://www.strava.com/api/v3/activities/10531467027?include_all_efforts=false';
            $token = '5a525bfc7d1210250d6727f701cb91db34d19300';
            $response += Http::withToken($token)->get($url)->json();
            // dd($response);

            $user = $this->getUserByStravaId(100148951);

            $finishTime = $resultService->dataFromStravaStream($response, $registration, $user->id);

            $result = new Result();
            $result->registration_id = $finishTime['registration_id'];
            $result->finish_time_date = $finishTime['finish_time_date'];
            $result->finish_time = $finishTime['finish_time'];
            $result->average_time_per_km = $finishTime['average_time_per_km'];
            $result->finish_time_sec = $finishTime['finish_time_sec'];
            // $result->duplicity_check = $finishTime['duplicity_check'];
            $result->place = 'Nevim';

            // dd($result);

            DB::beginTransaction();

            try {
                $result->save();
            } catch (QueryException $e) {
                dd($e);

                return back()->withError('Došlo k problému s nahráním souboru, kontaktujte timechip.cz@gmail.com')->withInput();
            }

            for ($i = 0; $i < count($finishTime['track_points']); $i++) {
                $finishTime['track_points'][$i]['result_id'] = $result->id;
            }

        // $trackPoint::insert($finishTime['track_points']);

            try {
                $trackPoint::insert($finishTime['track_points']);
                DB::commit();
            } catch (UniqueConstraintViolationException $e) {

                dd($e);
                if ($e->errorInfo[1] == 1062) {
                    DB::rollback();

                    return back()->withError('Soubor obsahuje duplicitní časové údaje')->withInput();
                }
            }

            $r = Result::where('registration_id', $finishTime['registration_id'])
                ->orderBy('finish_time', 'asc')
                ->get();

            $lastId = $result->id;
            foreach ($r as $key => $value) {
                if ($value->id == $lastId) {
                    $rank = $key + 1;
                }

                Result::where('id', $value->id)->update(['finish_time_order' => $key + 1]);
            }

        }

    }

    public function enableStrava(Request $request)
    {
        return redirect('https://www.strava.com/oauth/authorize?client_id=117954&response_type=code&redirect_uri=https://virtual-run.cz/redirect-strava/'.$request->user()->id.'&approval_prompt=force&scope=activity:read_all');
    }
}
