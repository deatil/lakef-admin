<?php

declare(strict_types=1);

use App\Admin\Extension\Admin;
use App\Serverlog\Controller;

include_once 'helper.php';

Admin::routes(function ($router) {
    // 日志
    $router->get('/serverlog/index', [Controller\Index::class, 'getIndex']);
    // $router->post('/passport/login', [Controller\Passport::class, 'postLogin']);

});
