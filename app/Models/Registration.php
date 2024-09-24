<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Registration extends Model
{
    protected $fillable = ['event_id', 'user_id', 'category_id','ids'];

    // V modelu Registration
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function someRegistrationExists($user_id,  $event_id)
    {
        return self::where('user_id', $user_id)
        ->whereHas('event', function ($query) use ($event_id) {
            $query->where('serie_id', function ($subquery) use ($event_id) {
                $subquery->select('serie_id')
                         ->from('events')
                         ->where('id', $event_id)
                         ->limit(1);
            });
        })
        ->exists();
    }



    public function startNumber($event_id,$user_id)
    {
       
     


    $user_exists_ids = self::select('ids')
    ->where('user_id', $user_id)
    ->whereIn('event_id', function($query) {
        $query->select('id')
            ->from('events')
            ->where('serie_id', function($subQuery) {
                $subQuery->select('serie_id')
                    ->from('events')
                    ->where('id', 5);
            });
    })
    ->pluck('ids')
    ->first();

    if(!is_null($user_exists_ids))
    {
        return $user_exists_ids;
    }
    else
    {
        $max_ids =  self::whereHas('event', function ($query) use ($event_id) {
            $query->where('serie_id', function ($subquery) use ($event_id) {
                $subquery->select('serie_id')
                         ->from('events')
                         ->where('id', $event_id)
                         ->limit(1);
            });
        })
        ->max("ids");

        if($max_ids == null)
        {
            return 1;
        }
        else
        {
            return $max_ids + 1;
        }

    }


       
       
       
    }




    public function eventRegistrationExists($userId, $eventId)
    {
        return self::where(['user_id' => $userId, 'event_id' => $eventId])->exists();
    }

   //na kazdy zavod zvlast
    public function registrationExists($userId, $eventId, $platformId, $serieId)
    {

        if(is_null($serieId))
        {
            $return =  self::where(['user_id' => $userId,'event_id' => $eventId])->first('id');
        }
        else
        {
            $return = self::join('events as e', 'e.id', '=', 'registrations.event_id')
                ->where('e.serie_id', $serieId)
                ->where('e.platform_id', $platformId)
                ->where('registrations.user_id', $userId)
                ->select('registrations.event_id')
                ->get();


        }

        return $return;
    }

    public function bulkInsert(array $registrations)
    {
        return self::insert($registrations);
    }



}
