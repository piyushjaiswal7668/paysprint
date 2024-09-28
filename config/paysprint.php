<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Mode
    |--------------------------------------------------------------------------
    |
    | This sets the mode for the API. Options: 'live', 'uat'
    |
    */
    'mode' => env('API_MODE', 'uat'),  // Default to UAT, can be changed to 'live' in .env file

    /*
    |--------------------------------------------------------------------------
    | API URLs
    |--------------------------------------------------------------------------
    |
    | URLs for the live and UAT environments
    |
    */
    'urls' => [
        'live' => env('API_LIVE_URL', 'https://api.paysprint.in'),
        'uat' => env('API_UAT_URL', 'https://sit.paysprint.in/service-api'),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Authorized Key
    |--------------------------------------------------------------------------
    |
    | This is the authorized key to use for making API calls.
    |
    */
    'authorised_key' => env('API_AUTHORISED_KEY', 'your-authorised-key'),
    /*
    |--------------------------------------------------------------------------
    | JWT Settings
    |--------------------------------------------------------------------------
    |
    | Partner ID and JWT Key for generating JWT.
    |
    */
    'sprint_partner_id' => env('SPRINT_PARTNER_ID', 'your-partner-id'),
    'sprint_jwt_key' => env('SPRINT_JWT_KEY', 'your-jwt-key'),

];
