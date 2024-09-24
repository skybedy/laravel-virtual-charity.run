<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Registration;

class CheckUserSerieRegistered
{
    public function handle(Request $request, Closure $next): Response
    {
        $user_id = $request->user()->id;

        $event_id = $request->route('eventId');

        $exists = Registration::where(['event_id' => $event_id, 'user_id' => $user_id])->exists();

        if($exists)
        {
            return $next($request);
        }

        return redirect()->route('registration.create',$event_id)->with('error', 'Je nutné nejdříve se k závodům registrovat a uhradit startovné.');
    }



}
