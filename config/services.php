<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'facebook' => [
        'client_id' => '709655243881130',
        'client_secret' => '422e616f1441a4830096892038f60973',
        'redirect' => 'https://virtual-run.cz/auth/facebook/callback',
    ],

    'google' => [
        'client_id' => '81604204242-69c7g24se3ogved24mm0btqi62s8sl9a.apps.googleusercontent.com',
        'client_secret' => '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',
        'redirect' => 'http://localhost:81/auth/google/callback',
        //'redirect' => 'https://virtual-run.cz/auth/google/callback',
    ],

];
