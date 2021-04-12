<?php

declare(strict_types=1);

use Hyperf\HttpServer\Router\Router;
use App\Admin\Controller;

include_once 'helper.php';

// 后台
Router::addGroup('/admin/', function ($router) {
    $router->get('passport/captcha', [Controller\Passport::class, 'getCaptcha']);
    $router->get('passport/login', [Controller\Passport::class, 'getLogin']);
    $router->post('passport/login', [Controller\Passport::class, 'postLogin']);
    $router->get('passport/logout', [Controller\Passport::class, 'getLogout']);

    $router->get('index', [Controller\Index::class, 'getIndex']);
    $router->get('index/main', [Controller\Index::class, 'getMain']);
    $router->get('index/menu', [Controller\Index::class, 'getMenu']);
    $router->post('index/clear', [Controller\Index::class, 'postClear']);

}, [
    // 中间件
    'middleware' => [
        \App\Admin\Middleware\Auth::class,
    ],
]);
