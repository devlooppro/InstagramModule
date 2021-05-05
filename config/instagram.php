<?php

return [
    'login' => env('INSTAGRAM_LOGIN'),
    'password' => env('INSTAGRAM_PASSWORD'),
    'profile' => env('INSTAGRAM_PROFILE', 'feloniukclinic'),
    'download-folders' => [
        '🤍'
    ],

    // 0 - no limit
    'max-stories-per-folder' => 15,

    //Path for media download
    'download_path' => 'public/instagram/media',
];
