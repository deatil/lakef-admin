<?php

declare (strict_types = 1);

namespace App\Admin\Extension;

use Closure;

use Hyperf\HttpServer\Router\Router;

/**
 * 后台扩展
 */
class Admin
{
    /**
     * 设置盐
     */
    public static function routes(Closure $callback)
    {
        // 定义路由
        Router::addGroup(
            config('admin.route.group'), 
            $callback, 
            [
                // 中间件
                'middleware' => config('admin.route.middleware'),
            ]
        );
    }
}
