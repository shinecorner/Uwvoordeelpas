<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('mail.uwvoordeelpas.nl'),
        'secret' => env('https://api.mailgun.net/v3/mail.uwvoordeelpas.nl'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => App\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'facebook' => [
        'client_id' => '1208660282593816',
        'client_secret' => '5d47af1b3a44c06b1b483439870fdb12',
        'redirect' => env('FB_REDIRECT_URL')
    ],

    'google' => [
        'client_id' => '676252610698-hkdvr21lmjo28mng7smk5l55ajgjcs6o.apps.googleusercontent.com',
        'client_secret' => 'pV4b99QKXzrAwhxgacWY4y4G',
        'redirect' => env('GOOGLE_REDIRECT_URL')
    ],

];
