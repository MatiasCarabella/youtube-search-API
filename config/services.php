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

    'youtube' => [
        'api_key' => env('YOUTUBE_API_KEY'),
        'results' => [
            'default' => env('YOUTUBE_RESULTS_DEFAULT', 10),
            'min' => env('YOUTUBE_RESULTS_MIN', 1),
            'max' => env('YOUTUBE_RESULTS_MAX', 10),
        ],
    ],

];
