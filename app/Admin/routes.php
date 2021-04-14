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
    $router->get('role/data', [Controller\Role::class, 'getData']);
    $router->get('role/create', [Controller\Role::class, 'getCreate']);
    $router->post('role/create', [Controller\Role::class, 'postCreate']);
    $router->get('role/update', [Controller\Role::class, 'getUpdate']);
    $router->post('role/update', [Controller\Role::class, 'postUpdate']);
    $router->post('role/delete', [Controller\Role::class, 'postDelete']);

    // 权限
    $router->get('permission/index', [Controller\Permission::class, 'getIndex']);
    $router->get('permission/data', [Controller\Permission::class, 'getData']);
    $router->get('permission/create', [Controller\Permission::class, 'getCreate']);
    $router->post('permission/create', [Controller\Permission::class, 'postCreate']);
    $router->get('permission/update', [Controller\Permission::class, 'getUpdate']);
    $router->post('permission/update', [Controller\Permission::class, 'postUpdate']);
    $router->post('permission/delete', [Controller\Permission::class, 'postDelete']);
    $router->post('permission/sort', [Controller\Permission::class, 'postSort']);

}, [
    // 中间件
    'middleware' => [
        \App\Admin\Middleware\Auth::class,
    ],
]);
