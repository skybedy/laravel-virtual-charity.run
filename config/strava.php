<?php


return [
        'stream' => [
                    'url' => 'https://www.strava.com/api/v3/activities/',
                    'params' => '/streams?keys=time,latlng,altitude&key_by_type=true',
                ],
        'activity' => [
                    'url' => 'https://www.strava.com/api/v3/activities/',
                    'params' => '?include_all_efforts=true',
        ],
        'token' => [
                    'url' => 'https://www.strava.com/oauth/token',
                    'params' => [
                        'client_id' => '117954',
                        'client_secret' => 'a56df3b8bb06067ebe76c7d23af8ee8211d11381',
                        'refresh_token' => '',
                        'grant_type' => 'refresh_token',
                    ]
                ]

        ];
