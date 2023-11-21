<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    
    
    public function resultsOverall($eventId)
    {
        return self::join('registrations', 'results.registration_id', '=', 'registrations.id')
        ->join('users', 'registrations.user_id', '=', 'users.id')
        ->selectRaw('min(results.finishtime) AS best_finish_time, registrations.id AS registration_id, users.firstname, users.lastname')
        ->where('registrations.event_id', $eventId)
        ->groupBy('registrations.id')
        ->orderBy('best_finish_time')
        ->get();
    
    }
    
    
    
    public function finishTimeOrder($registrationId)
    {
        return self::where(['registration_id' => $registrationId])->first('id') + 1;
    }







  
}
