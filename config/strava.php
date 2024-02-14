<?php


return [

        'client_id' => env('STRAVA_CLIENT_ID'),

        'client_secret' => env('STRAVA_CLIENT_SECRET'),

        'stream' => [
                    'url' => 'https://www.strava.com/api/v3/activities/',
                    'params' => '/streams?keys=time,latlng,altitude,cadence&key_by_type=true',
                ],

        'activity' => [
                    'url' => 'https://www.strava.com/api/v3/activities/',
                    'params' => '?include_all_efforts=true',
                ],

        'token' => [
                    'url' => 'https://www.strava.com/oauth/token',
                    'params' => [
                        'client_id' => env('STRAVA_CLIENT_ID'),
                        'client_secret' => env('STRAVA_CLIENT_SECRET'),
                        'grant_type' => 'refresh_token',
                ]
            ]

        ];
