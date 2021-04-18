<?php

declare(strict_types=1);

use Hyperf\HttpServer\Router\Router;
use App\Admin\Controller;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

Router::get('/favicon.ico', function () {
    return '';
});

include_once BASE_PATH . '/app/Admin/routes.php';
include_once BASE_PATH . '/app/Serverlog/routes.php';
