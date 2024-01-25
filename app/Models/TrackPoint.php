<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackPoint extends Model
{
    public function result()
    {
        return $this->belongsTo(Result::class);
    }
}
