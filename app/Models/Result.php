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
            ->selectRaw('results.id,MIN(SUBSTRING(results.finish_time,2)) AS best_finish_time,results.finish_time_sec as best_finish_time_sec,DATE_FORMAT(results.finish_time_date,"%e.%c.") AS date, registrations.id AS registration_id, users.firstname, users.lastname, users.team, users.id AS user_id,categories.name AS category_name,results.pace,COUNT(results.id) AS count')
            ->where('registrations.event_id', $eventId)
            ->groupBy('registrations.id')
            ->orderBy('best_finish_time')
            ->get();
    }

    public function resultsIndividual($registrationId)
    {
        return self::selectRaw('SUBSTRING(finish_time,2) AS finish_time,pace,DATE_FORMAT(results.finish_time_date,"%e.%c.") AS date')
            ->where('registration_id', $registrationId)
            ->orderBy('finish_time','ASC')
            ->skip(1)
            ->take(PHP_INT_MAX)
            ->get();
    }


    public function finishTimeOrder($registrationId)
    {
        return self::where(['registration_id' => $registrationId])->first('id') + 1;
    }




}
