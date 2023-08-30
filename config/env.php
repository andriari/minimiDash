<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Environment Cache
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'APP_NAME' => env('APP_NAME'),
    'APP_ENV' => env('APP_ENV'),
    'APP_KEY' => env('APP_KEY'),
    'APP_DEBUG' => env('APP_DEBUG'),
    'APP_URL' => env('APP_URL'),
    'API_URL' => env('API_URL'),
    'FRONTEND_URL' => env('FRONTEND_URL'),
    'DASHBOARD_KEY' => env('DASHBOARD_KEY'),
    'COOKIE_DOMAIN' => env('COOKIE_DOMAIN'),
    'COOKIE_TOKEN' => env('COOKIE_TOKEN'),
    'COOKIE_MINUTES' => env('COOKIE_MINUTES'),
    'COOKIE_LANG' => env('COOKIE_LANG'),
    'LOG_CHANNEL' => env('LOG_CHANNEL'),
    'DB_CONNECTION' => env('DB_CONNECTION'),
    'DB_HOST' => env('DB_HOST'),
    'DB_PORT' => env('DB_PORT'),
    'DB_DATABASE' => env('DB_DATABASE'),
    'DB_USERNAME' => env('DB_USERNAME'),
    'DB_PASSWORD' => env('DB_PASSWORD'),
    'BROADCAST_DRIVER' => env('BROADCAST_DRIVER'),
    'CACHE_DRIVER' => env('CACHE_DRIVER'),
    'QUEUE_CONNECTION' => env('QUEUE_CONNECTION'),
    'SESSION_DRIVER' => env('SESSION_DRIVER'),
    'SESSION_LIFETIME' => env('SESSION_LIFETIME'),
    'REDIS_HOST' => env('REDIS_HOST'),
    'REDIS_PASSWORD' => env('REDIS_PASSWORD'),
    'REDIS_PORT' => env('REDIS_PORT'),
    'MAIL_DRIVER' => env('MAIL_DRIVER'),
    'MAIL_HOST' => env('MAIL_HOST'),
    'MAIL_PORT' => env('MAIL_PORT'),
    'MAIL_USERNAME' => env('MAIL_USERNAME'),
    'MAIL_PASSWORD' => env('MAIL_PASSWORD'),
    'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION'),
    'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS'),
    'MAIL_FROM_NAME' => env('MAIL_FROM_NAME'),
    'AWS_ACCESS_KEY_ID' => env('AWS_ACCESS_KEY_ID'),
    'AWS_SECRET_ACCESS_KEY' => env('AWS_SECRET_ACCESS_KEY'),
    'AWS_DEFAULT_REGION' => env('AWS_DEFAULT_REGION'),
    'AWS_BUCKET' => env('AWS_BUCKET'),
    'PUSHER_APP_ID' => env('PUSHER_APP_ID'),
    'PUSHER_APP_KEY' => env('PUSHER_APP_KEY'),
    'PUSHER_APP_SECRET' => env('PUSHER_APP_SECRET'),
    'PUSHER_APP_CLUSTER' => env('PUSHER_APP_CLUSTER'),
    'MIX_PUSHER_APP_KEY' => env('MIX_PUSHER_APP_KEY'),
    'MIX_PUSHER_APP_CLUSTER' => env('MIX_PUSHER_APP_CLUSTER'),
    'RE_CAP_SITE' => env('RE_CAP_SITE'),
    'RE_CAP_SECRET' => env('RE_CAP_SECRET'),

];
