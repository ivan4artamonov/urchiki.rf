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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
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

    /*
    |--------------------------------------------------------------------------
    | OAuth: вход через соцсети (Laravel Socialite + провайдеры)
    |--------------------------------------------------------------------------
    |
    | Redirect URI в кабинете каждого сервиса должен совпадать с полем redirect
    | (или с явным значением *_REDIRECT_URI в .env).
    |
    */

    'vkontakte' => [
        'client_id' => env('VKONTAKTE_CLIENT_ID'),
        'client_secret' => env('VKONTAKTE_CLIENT_SECRET'),
        'redirect' => env('VKONTAKTE_REDIRECT_URI', rtrim((string) env('APP_URL', 'http://localhost'), '/').'/auth/social/vkontakte/callback'),
    ],

    'yandex' => [
        'client_id' => env('YANDEX_CLIENT_ID'),
        'client_secret' => env('YANDEX_CLIENT_SECRET'),
        'redirect' => env('YANDEX_REDIRECT_URI', rtrim((string) env('APP_URL', 'http://localhost'), '/').'/auth/social/yandex/callback'),
    ],

    'mailru' => [
        'client_id' => env('MAILRU_CLIENT_ID'),
        'client_secret' => env('MAILRU_CLIENT_SECRET'),
        'redirect' => env('MAILRU_REDIRECT_URI', rtrim((string) env('APP_URL', 'http://localhost'), '/').'/auth/social/mailru/callback'),
    ],

];
