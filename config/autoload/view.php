<?php

declare(strict_types=1);

use Hyperf\View\Mode;
use Hyperf\ViewEngine\HyperfViewEngine;

return [
    // 使用的渲染引擎
    'engine' => HyperfViewEngine::class,
    // 不填写则默认为 Task 模式，推荐使用 Task 模式 SYNC | TASK
    'mode' => Mode::TASK,
    'config' => [
        // 若下列文件夹不存在请自行创建
        'view_path' => BASE_PATH . '/storage/view/',
        'cache_path' => BASE_PATH . '/runtime/view/',
        'charset' => 'UTF-8',
    ],

    # 自定义组件注册
    'components' => [
        // 'alert' => \App\View\Components\Alert::class
    ],

    # 视图命名空间 (主要用于扩展包中)
    'namespaces' => [
        // 'admin' => BASE_PATH . '/module/Admin/view',
    ],
];