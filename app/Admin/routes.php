<?php

declare(strict_types=1);

use Hyperf\HttpServer\Router\Router;
use App\Admin\Controller;

include_once 'helper.php';

Router::addGroup('/admin/', function ($router) {
    // 登陆
    $router->get('passport/captcha', [Controller\Passport::class, 'getCaptcha']);
    $router->get('passport/login', [Controller\Passport::class, 'getLogin']);
    $router->post('passport/login', [Controller\Passport::class, 'postLogin']);
    $router->get('passport/logout', [Controller\Passport::class, 'getLogout']);
    
    // 首页
    $router->get('index', [Controller\Index::class, 'getIndex']);
    $router->get('index/main', [Controller\Index::class, 'getMain']);
    $router->get('index/menu', [Controller\Index::class, 'getMenu']);
    $router->post('index/clear', [Controller\Index::class, 'postClear']);

    // 个人资料
    $router->get('profile/setting', [Controller\Profile::class, 'getSetting']);
    $router->post('profile/setting', [Controller\Profile::class, 'postSetting']);
    $router->get('profile/password', [Controller\Profile::class, 'getPassword']);
    $router->post('profile/password', [Controller\Profile::class, 'postPassword']);

    // 角色
    $router->get('role/index', [Controller\Role::class, 'getIndex']);
    $router->post('role/index', [Controller\Role::class, 'postIndex']);
    $router->get('role/create', [Controller\Role::class, 'getCreate']);
    $router->post('role/create', [Controller\Role::class, 'postCreate']);
    $router->get('role/update', [Controller\Role::class, 'getUpdate']);
    $router->post('role/update', [Controller\Role::class, 'postUpdate']);
    $router->post('role/delete', [Controller\Role::class, 'postDelete']);

}, [
    // 中间件
    'middleware' => [
        \App\Admin\Middleware\Auth::class,
    ],
]);
