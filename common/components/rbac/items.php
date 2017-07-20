<?php
return [
    'admin' => [
        'type' => 1,
        'ruleName' => 'userRole',
        'children' => [
            'dashboard',
            'user',
        ],
    ],
    'user' => [
        'type' => 1,
        'ruleName' => 'userRole',
    ],
    'dashboard' => [
        'type' => 2,
        'description' => 'Admin panel',
    ],
];
