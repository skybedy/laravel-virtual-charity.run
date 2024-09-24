<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Startlist extends Model
{
    public function startlist($eventId)
    {
        return User::select('registrations.ids','users.lastname', 'users.firstname', 'users.team', 'categories.name')
            ->join('registrations', 'users.id', '=', 'registrations.user_id')
            ->join('events', 'events.id', '=', 'registrations.event_id')
            ->join('categories', 'registrations.category_id', '=', 'categories.id')
            ->where('events.id', $eventId)
            ->orderBy('registrations.ids', 'asc')
            ->get();
    }
}
