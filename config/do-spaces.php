<?php

return [
    'key' => env('DO_SPACES_ACCESS_KEY'),
    'secret' => env('DO_SPACES_SECRET'),
    'region' => env('DO_SPACES_REGION'),
    'bucket' => env('DO_SPACES_BUCKET'),
    'url' => env('DO_SPACES_URL'),
    'endpoint' => env('DO_SPACES_ENDPOINT'),
    'use_path_style_endpoint' => env('DO_SPACES_USE_PATH_STYLE_ENDPOINT', false),
];
