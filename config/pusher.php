<?php

return [
    'app_id' => env('PUSHER_APP_ID'),
    'app_key' => env('PUSHER_APP_KEY'),
    'app_secret' => env('PUSHER_APP_SECRET'),
    'app_cluster' => env('PUSHER_APP_CLUSTER'),
    'options' => [
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'useTLS' => true,
    ],
];
