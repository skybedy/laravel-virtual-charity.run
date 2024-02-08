<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Result extends Model
{
    protected $casts = [
        'duplicity_check' => 'array',
    ];

    public function trackPoints()
    {
        return $this->hasMany(TrackPoint::class);
    }

    public function resultsOverall($eventId)
    {
        return self::join('registrations', 'results.registration_id', '=', 'registrations.id')
            ->join('users', 'registrations.user_id', '=', 'users.id')
            ->join('categories', 'registrations.category_id', '=', 'categories.id')
            ->selectRaw('results.id,MIN(SUBSTRING(results.finish_time,2)) AS best_finish_time,results.finish_time_sec as best_finish_time_sec,DATE_FORMAT(results.finish_time_date,"%e.%c.") AS date, registrations.id AS registration_id, users.firstname, users.lastname, users.team, categories.name AS category_name,results.pace')
            ->where('registrations.event_id', $eventId)
            ->groupBy('registrations.id')
            ->orderBy('best_finish_time')
            ->get();

    }

    public function resultsIndividual($eventId,$userId)
    {
        //return self::join('registrations', 'results.registration_id', '=', 'registrations.id')

        dd(self::select('results.id', 'results.finish_time', 'results.finish_time_sec', 'results.finish_time_date', 'results.pace', 'registrations.id AS registration_id', 'users.firstname', 'users.lastname', 'users.team', 'categories.name AS category_name')
            ->join('registrations', 'results.registration_id', '=', 'registrations.id')
            ->join('users', 'registrations.user_id', '=', 'users.id')
            ->join('categories', 'registrations.category_id', '=', 'categories.id')
            ->where('registrations.event_id', $eventId)
            ->where('users.id', $userId)
            ->orderBy('results.finish_time_sec')
            ->get());

    }






    public function finishTimeOrder($registrationId)
    {
        return self::where(['registration_id' => $registrationId])->first('id') + 1;
    }




}
