<?php

declare(strict_types=1);

return [
    'admin' => [
        'title' => 'ServerLog',
        'logo' => 'images/logo.png',
        'version' => '1.0.0',
    ],
    'passport' => [
        'salt' => 'd704e003b86bacd701575f73a3a95dcb',
        'super_id' => 1,
    ],
    'auth' => [
        // 登陆认证过滤
        'authenticate_excepts' => [
        
        ],
        // 权限认证过滤
        'permission_excepts' => [
        
        ],
    ],
];
