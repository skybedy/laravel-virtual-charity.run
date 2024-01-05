<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class WebhookController extends Controller
{



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



}
