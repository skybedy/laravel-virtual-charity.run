<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RedirectController extends Controller
{
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

        $user = User::find($request->userId);
        $user->strava_id = $content['athlete']['id'];
        $user->strava_access_token = $content['access_token'];
        $user->strava_refresh_token = $content['refresh_token'];
        $user->strava_expires_at = $content['expires_at'];
        $user->strava_scope = $request->query('scope');
        $user->save();

        dd($user);

        // return view('redirect.redirect-strava');
        //  return redirect()->back();

    }

    //return view('redirect.redirect-strava');

}
