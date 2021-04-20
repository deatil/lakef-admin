<?php

declare(strict_types=1);

use Hyperf\HttpServer\Router\Router;
use App\Serverlog\Controller;

include_once 'helper.php';

Router::addGroup(config('serverlog.route.group'), function ($router) {
    
    // 接收日志
    $router->post('/add', [Controller\Index::class, 'postAdd']);

}, [
    // 中间件
    'middleware' => config('serverlog.route.middleware'),
]);
