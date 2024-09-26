<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use HasFactory;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'registrations', 'event_id', 'user_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'event_id');
    }

    public function eventList($userId)
    {
        return self::select('events.id','events.serie_id', 'events.name', 'events.date_start', 'events.date_end', 'events.second_name')
            ->leftJoin('registrations', function ($join) use ($userId) {
                $join->on('events.id', '=', 'registrations.event_id')
                    ->where('registrations.user_id', '=', $userId);
            })
            ->where(['events.platform_id' =>  env('PLATFORM_ID'),'display' => true])
            ->selectRaw('CASE WHEN registrations.user_id IS NULL THEN null ELSE 1 END as registration_status')
            ->orderBy('events.id', 'asc')
            ->get();
    }

    /**
     * Get all events from the same series only
     *
     * @param $eventId
     * @return mixed
     */
    public function allSameSeriesEvents($eventId)
    {

        return self::where('id', '!=', $eventId)
            ->where('serie_id', function ($query) use($eventId) {
                $query->select('serie_id')
                    ->from('events')
                    ->where('id', $eventId)
                    ->limit(1);
            })
            ->select("id","name")
            ->get();
    }


}
