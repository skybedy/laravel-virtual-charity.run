<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    public function categoryChoice($gender, $age)
    {
        if ($age > 100) {
            return self::where('gender', $gender)
                ->where('open', '=', 1)
                ->first('id');
        } else {
            return self::where('gender', $gender)
                ->where('age_start', '<=', $age)
                ->where('age_end', '>=', $age)
                ->first('id');
        }

    }

    public function categoryListAbsolute($eventId)
    {
        $users = DB::table('users as u')
            ->join('registrations as r', 'r.user_id', '=', 'u.id')
            ->join('categories as c', 'r.category_id', '=', 'c.id')
            ->where('r.event_id', $eventId)
            ->orderBy('u.lastname', 'asc')
            ->get(['u.lastname', 'u.firstname', 'c.name']);

        return $users;

    }
}
