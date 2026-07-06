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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google_drive_lampiran' => [
        'enabled' => env('GOOGLE_DRIVE_LAMPIRAN_ENABLED', false),
        'auth_mode' => env('GOOGLE_DRIVE_AUTH_MODE', 'service_account'),
        'credentials_path' => env('GOOGLE_DRIVE_SERVICE_ACCOUNT_JSON'),
        'client_id' => env('GOOGLE_DRIVE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_DRIVE_CLIENT_SECRET'),
        'refresh_token' => env('GOOGLE_DRIVE_REFRESH_TOKEN'),
        'token_uri' => env('GOOGLE_DRIVE_TOKEN_URI', 'https://oauth2.googleapis.com/token'),
        'lampiran_folder_id' => env('GOOGLE_DRIVE_LAMPIRAN_FOLDER_ID'),
        'keep_local_copy' => env('GOOGLE_DRIVE_KEEP_LOCAL_COPY', true),
        'timeout' => env('GOOGLE_DRIVE_TIMEOUT', 30),
    ],

];
