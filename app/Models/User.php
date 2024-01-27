<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'team',
        'gender',
        'birth_year',
        'email',
        'password',
        'facebook_id',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'registrations', 'user_id', 'event_id');
    }

    public function updateStravaToken($userId,$stravaToken)
    {
        $user = User::find($userId);

        $user->strava_access_token = $stravaToken['access_token'];

        $user->strava_refresh_token = $stravaToken['refresh_token'];

        $user->strava_expires_at = $stravaToken['expires_at'];

        $user->save();

        return $user->strava_access_token;

    }







}
