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
            'gdocs'  => '/^(docx?|xlsx?|pptx?|pps|potx?|rtf|ods|odt|pages|ai|dxf|ttf|tiff?|wmf|e?ps)$/i',
            'text'   => '/^(txt|md|csv|nfo|ini|json|php|js|css|ts|sql)$/i',
            'video'  => '/^(og?|mp4|webm|mp?g|mov|3gp)$/i',
            'audio'  => '/^(og?|mp3|mp?g|wav)$/i',
            'pdf'    => '/^(pdf)$/i',
            'flash'  => '/^(swf)$/i',
        ],
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
