<?php

/**
 * @Author: tianwangchong
 * @Date:   2018-05-07 14:27:14
 * @Last Modified by:   tianwangchong
 * @Last Modified time: 2018-05-07 20:10:46
 */

return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],

    'guards' => [
        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => \App\Models\User::class
        ]
    ]
];