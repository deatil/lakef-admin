<?php

declare(strict_types=1);

return [
    // 系统信息
    'system' => [
        'title' => 'Lakef-Admin',
        'logo' => 'images/logo.png',
        'version' => '1.0.1',
    ],
    
    // 路由
    'route' => [
        'group' => '/admin',
        'middleware' => [
            \Lakef\Admin\Middleware\Auth::class,
            \Lakef\Admin\Middleware\Permission::class,
            \Lakef\Admin\Middleware\OperationLog::class,
        ],
    ],
    
    // 登陆
    'passport' => [
        'salt' => 'd704e003b86bacd701575f73a3a95dcb',
        'super_id' => 1,
    ],
    
    // 认证
    'auth' => [
        // 登陆认证过滤
        'authenticate_excepts' => [
        
        ],
        // 权限认证过滤
        'permission_excepts' => [
        
        ],
    ],
    
    // 资源
    'assets' => [
        'static' => '/static/',
    ],
    
    // 上传
    'upload' => [
        'disk' => 'public',
        
        'directory' => [
            'image' => 'images',
            'media' => 'medias',
            'file' => 'files',
        ],
        
        'file_types' => [
            'image'  => '/^(gif|png|jpe?g|svg|webp)$/i',
            'html'   => '/^(htm|html)$/i',
            'office' => '/^(docx?|xlsx?|pptx?|pps|potx?)$/i',
            'docs'  => '/^(docx?|xlsx?|pptx?|pps|potx?|rtf|ods|odt|pages|ai|dxf|ttf|tiff?|wmf|e?ps)$/i',
            'text'   => '/^(txt|md|csv|nfo|ini|json|php|js|css|ts|sql)$/i',
            'video'  => '/^(og?|mp4|webm|mp?g|mov|3gp)$/i',
            'audio'  => '/^(og?|mp3|mp?g|wav)$/i',
            'pdf'    => '/^(pdf)$/i',
            'flash'  => '/^(swf)$/i',
        ],
    ],
];
