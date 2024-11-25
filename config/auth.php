<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'), 
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [
        'profesores' => [
            'driver' => 'session',
            'provider' => 'profesores',
        ],
        'estudiantes' => [
            'driver' => 'session',
            'provider' => 'estudiantes',
        ],
        'acudientes' => [
            'driver' => 'session',
            'provider' => 'acudientes',
        ],
        'directivos' => [
            'driver' => 'session',
            'provider' => 'directivos',
        ],
    ],

    'providers' => [
        'profesores' => [
            'driver' => 'eloquent',
            'model' => App\Models\profesores::class,
        ],
        'estudiantes' => [
            'driver' => 'eloquent',
            'model' => App\Models\estudiantes::class,
        ],
        'acudientes' => [
            'driver' => 'eloquent',
            'model' => App\Models\acudientes::class,
        ],
        'directivos' => [
            'driver' => 'eloquent',
            'model' => App\Models\directivos::class,
        ],
    ],

    'passwords' => [
        'profesores' => [
            'provider' => 'profesores',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'estudiantes' => [
            'provider' => 'estudiantes',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'acudientes' => [
            'provider' => 'acudientes',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'directivos' => [
            'provider' => 'directivos',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];

