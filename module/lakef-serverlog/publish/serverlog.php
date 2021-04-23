<?php

declare(strict_types=1);

return [
    // 应用
    'app' => [
        // 字符最大7位
        'prefix' => 'SL',
    ],
    
    // 路由
    'route' => [
        'group' => '/server-log',
        'middleware' => [
        ],
    ],
];
