<?php

return [

    /*
    | Where "Become a sponsor" inquiries are notified by email.
    | Comma-separated list; leave empty to only store them in the database.
    */
    'notify_emails' => array_filter(array_map('trim', explode(',', (string) env('EQUINOX_NOTIFY_EMAILS', '')))),

    /*
    | HTTP Basic credentials for the CSV exports under /admin.
    | If the password is empty, the admin routes respond 404.
    */
    'admin_user' => env('EQUINOX_ADMIN_USER', 'equinox'),
    'admin_password' => env('EQUINOX_ADMIN_PASSWORD'),

    /*
    | Footer social links. Only the ones with a URL are rendered.
    */
    'social' => array_filter([
        'LinkedIn' => env('EQUINOX_LINKEDIN_URL'),
        'Instagram' => env('EQUINOX_INSTAGRAM_URL'),
        'X' => env('EQUINOX_X_URL'),
        'YouTube' => env('EQUINOX_YOUTUBE_URL'),
    ]),

];
