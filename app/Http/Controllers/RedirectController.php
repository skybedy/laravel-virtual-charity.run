<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class RedirectController extends Controller
{
    public function redirectStrava()
    {
        
        $url = "https://www.strava.com/activities/10438527866/export_gpx";

        $response = Http::get($url);

        if ($response->successful()) {
            $filename = "neneennsnew_activity_10438527866.gpx";
            $path = storage_path("app/public/$filename");
    
            file_put_contents($path, $response->body());
    
            //return response()->download($path, $filename);
        } else {
            dd('probe');
        }
    }
        
        
        
        
        
        
        
        //return view('redirect.redirect-strava');
    
}
