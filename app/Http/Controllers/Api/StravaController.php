<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StravaController extends Controller
{
    
    public function authorizeStrava(Request $request)
    {
        $callback = $request->query('callback');
        
        $user_id = $request->query('user_id');

        return redirect("https://www.strava.com/oauth/authorize?client_id=".env('STRAVA_CLIENT_ID')."&response_type=code&approval_prompt=force&scope=activity:read&redirect_uri=https://virtual-charity.run/redirect-strava/$user_id?callback=$callback");
    }
}
