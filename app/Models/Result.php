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
            ->selectRaw('MIN(results.id) AS id,MIN(SUBSTRING(results.finish_time,2)) AS best_finish_time,MIN(results.finish_time_sec) as best_finish_time_sec,DATE_FORMAT(MIN(results.finish_time_date),"%e.%c.") AS date, MIN(results.place) as place, registrations.id AS registration_id, MIN(users.firstname) AS firstname, MIN(users.lastname) AS lastname, MIN(users.team) AS team,MIN(categories.name) AS category_name,MIN(results.average_time_per_km) AS average_time_per_km')
            ->where('registrations.event_id', $eventId)
            ->groupBy('registrations.id')
            ->orderBy('best_finish_time')
            ->get();

    }

    public function finishTimeOrder($registrationId)
    {
        return self::where(['registration_id' => $registrationId])->first('id') + 1;
    }

    public function getDuplicityCheck($userId)
    {
        return DB::table('results as r')
            ->join('registrations as r2', 'r.registration_id', '=', 'r2.id')
            ->where('r2.user_id', $userId)
            ->select('r.duplicity_check')
            ->get();

    }
}
