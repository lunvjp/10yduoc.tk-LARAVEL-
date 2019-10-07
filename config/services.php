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
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'facebook' => [
        'client_id' => '309350989506967',
        'client_secret' => 'b6b4587b95346a671e4ad2705c06a5d5',
        'redirect' => '/auth/facebook/callback',
    ],

    'google' => [
        'client_id' => '308122676345-ks9bl51324cu0dmctboccfrokhlj6e60.apps.googleusercontent.com',
        'client_secret' => 'fAqp01rt5Q3709I0ETMBJ4i7',
        'redirect' => '/auth/google/callback',
    ],

];