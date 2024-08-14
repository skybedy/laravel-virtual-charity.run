<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = ['event_id', 'user_id', 'category_id'];

    // V modelu Registration
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function registrationExists($userId, $serieId)
    {
       // dd(self::where(['user_id' => $userId])->first('id'));

       return(self::join('events as e', 'e.id', '=', 'registrations.event_id')
            ->where('e.serie_id', $serieId)
            ->where('registrations.user_id', $userId)
            ->select('registrations.event_id')
            ->get());

    }
}
