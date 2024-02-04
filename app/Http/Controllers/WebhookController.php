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

class WebhookController extends Controller
{
    public function autouploadStrava(ResultService $resultService, Registration $registration, TrackPoint $trackPoint, Event $event)
    {

        $url = 'https://www.strava.com/api/v3/activities/10503391125?include_all_efforts=true';
        $token = 'f2c183e83f7e1528e6a135af1520c928cc304b00';
        $response = Http::withToken($token)->get($url);
        $data = $response->json();

        $finishTime = $resultService->dataStravaProcessing($response->json(), $registration);

        $result = new Result();
        $result->registration_id = $finishTime['registration_id'];
        $result->finish_time_date = $finishTime['finish_time_date'];
        $result->finish_time = $finishTime['finish_time'];
        $result->pace = $finishTime['pace'];
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

    public function postStrava(Request $request)
    {

        \Log::info('Webhook event received!', [
            'query' => $request->query(),
            'body' => $request->all(),
        ]);

        //$expiresAt = User::where(
        $stravaId = $request->input('owner_id');
        //$expiresAt = User::where('strava_id','=',$input)->value('expires_at');
        $user = User::select('id', 'strava_access_token', 'strava_refresh_token', 'strava_expires_at')->where('strava_id', $stravaId)->first();

        if ($user->strava_expires_at > time()) {

            $url = 'https://www.strava.com/api/v3/activities/'.$request->input('object_id').'?include_all_efforts=true';
            $token = $user->strava_access_token;
            $response = Http::withToken($token)->get($url);
            $data = $response->json();

        } else {
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

    /*

{"token_type":"Bearer","access_token":"f2c183e83f7e1528e6a135af1520c928cc304b00","expires_at":1704744984,"expires_in":21600,"refresh_token":"5a0de4bd8330b7b31e58c1895fd49d44ce111fc0"}skybedy@skybedy-Latitude-E6520:~$ ^C
*/

}
