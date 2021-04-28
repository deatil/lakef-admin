<?php

declare(strict_types=1);

use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');
Router::addRoute(['GET', 'POST', 'HEAD'], '/consumer', 'App\Controller\IndexController@consumer');

Router::get('/favicon.ico', function () {
    return '';
});
