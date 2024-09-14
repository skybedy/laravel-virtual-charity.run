<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class CheckUserSerieRegistered
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $userId = $request->user()->id;
        $serieId = env('ACTIVE_SERIE_ID');

        $exists = DB::table('registrations as r')
        ->join('events as e', 'r.event_id', '=', 'e.id')
        ->where('e.platform_id', env('PLATFORM_ID'))
        ->where('e.serie_id', env('ACTIVE_SERIE_ID'))
        ->where('r.user_id', $request->user()->id)
        ->exists();

        if($exists)
        {
            return $next($request);
        }

        return redirect()->route('registration.create')->with('error', 'Je nutné nejdříve se k závodům registrovat a uhradit startovné.');
}



}
