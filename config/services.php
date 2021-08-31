<?php
//$settingValues = \App\Setting::get();
//echo '<pre>';print_r( $settingValues );die;
return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, SparkPost and others. This file provides a sane default
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],
    'facebook' => [
     'client_id' => env('FACEBOOK_CLIENT_ID'),
     'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
     'redirect' => env('FACEBOOK_REDIRECT'),
   ], 
   'google' => [
     'client_id' => env('GOOGLE_CLIENT_ID'),
     'client_secret' => env('GOOGLE_CLIENT_SECRET'),
     'redirect' => env('GOOGLE_REDIRECT'),
   ],

    'paytm-wallet' => [
        'env' => env('PAYTM_ENVIRONMENT'), // values can be "local" or "production"
        'merchant_id' => env('YOUR_MERCHANT_ID'),
        'merchant_key' => env('YOUR_MERCHANT_KEY'),
        'merchant_website' => env('YOUR_WEBSITE'),
        'channel' => env('YOUR_CHANNEL'),
        'industry_type' => env('YOUR_INDUSTRY_TYPE'),
    ]
 
];
