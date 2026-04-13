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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'football_api' => [
        'key' => env('FOOTBALL_API_KEY'),
    ],

    'cedula' => [
        'id' => env('CEDULA_API_ID'),
        'token' => env('CEDULA_API_TOKEN'),
    ],

    'evolution' => [
        'url' => env('EVOLUTION_API_URL'),
        'key' => env('EVOLUTION_API_KEY'),
        'instance' => env('EVOLUTION_INSTANCE'),
    ],

    'football_data' => [
        'key' => env('FOOTBALL_DATA_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
