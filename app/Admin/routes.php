<?php

declare(strict_types=1);

use Hyperf\HttpServer\Router\Router;
use App\Admin\Controller;

include_once 'helper.php';

Router::addGroup(config('admin.route.group'), function ($router) {
    // 登陆
    $router->get('/passport/captcha', [Controller\Passport::class, 'getCaptcha']);
    $router->get('/passport/login', [Controller\Passport::class, 'getLogin']);
    $router->post('/passport/login', [Controller\Passport::class, 'postLogin']);
    $router->get('/passport/logout', [Controller\Passport::class, 'getLogout']);
    
    // 首页
    $router->get('/index', [Controller\Index::class, 'getIndex']);
    $router->get('/index/dashboard', [Controller\Index::class, 'getDashboard']);
    $router->get('/index/menu', [Controller\Index::class, 'getMenu']);
    $router->post('/index/clear', [Controller\Index::class, 'postClear']);

    // 个人资料
    $router->get('/profile/setting', [Controller\Profile::class, 'getSetting']);
    $router->post('/profile/setting', [Controller\Profile::class, 'postSetting']);
    $router->get('/profile/password', [Controller\Profile::class, 'getPassword']);
    $router->post('/profile/password', [Controller\Profile::class, 'postPassword']);
    
    // 文件上传
    $router->post('/upload/file', [Controller\Upload::class, 'postFile']);

    // 角色
    $router->get('/role/index', [Controller\Role::class, 'getIndex']);
    $router->get('/role/index-data', [Controller\Role::class, 'getIndexData']);
    $router->get('/role/tree', [Controller\Role::class, 'getTree']);
    $router->get('/role/tree-data', [Controller\Role::class, 'getTreeData']);
    $router->get('/role/create', [Controller\Role::class, 'getCreate']);
    $router->post('/role/create', [Controller\Role::class, 'postCreate']);
    $router->get('/role/update', [Controller\Role::class, 'getUpdate']);
    $router->post('/role/update', [Controller\Role::class, 'postUpdate']);
    $router->get('/role/access', [Controller\Role::class, 'getAccess']);
    $router->post('/role/access', [Controller\Role::class, 'postAccess']);
    $router->post('/role/delete', [Controller\Role::class, 'postDelete']);
    $router->post('/role/sort', [Controller\Role::class, 'postSort']);

    // 权限菜单
    $router->get('/permission/index', [Controller\Permission::class, 'getIndex']);
    $router->get('/permission/index-data', [Controller\Permission::class, 'getIndexData']);
    $router->get('/permission/menu', [Controller\Permission::class, 'getMenu']);
    $router->get('/permission/menu-data', [Controller\Permission::class, 'getMenuData']);
    $router->get('/permission/create', [Controller\Permission::class, 'getCreate']);
    $router->post('/permission/create', [Controller\Permission::class, 'postCreate']);
    $router->get('/permission/update', [Controller\Permission::class, 'getUpdate']);
    $router->post('/permission/update', [Controller\Permission::class, 'postUpdate']);
    $router->post('/permission/delete', [Controller\Permission::class, 'postDelete']);
    $router->post('/permission/sort', [Controller\Permission::class, 'postSort']);
    $router->post('/permission/setmenu', [Controller\Permission::class, 'postSetMenu']);

    // 管理员
    $router->get('/admin/index', [Controller\Admin::class, 'getIndex']);
    $router->get('/admin/index-data', [Controller\Admin::class, 'getIndexData']);
    $router->get('/admin/create', [Controller\Admin::class, 'getCreate']);
    $router->post('/admin/create', [Controller\Admin::class, 'postCreate']);
    $router->get('/admin/update', [Controller\Admin::class, 'getUpdate']);
    $router->post('/admin/update', [Controller\Admin::class, 'postUpdate']);
    $router->post('/admin/delete', [Controller\Admin::class, 'postDelete']);
    $router->get('/admin/password', [Controller\Admin::class, 'getPassword']);
    $router->post('/admin/password', [Controller\Admin::class, 'postPassword']);
    $router->get('/admin/access', [Controller\Admin::class, 'getAccess']);
    $router->post('/admin/access', [Controller\Admin::class, 'postAccess']);

    // 附件
    $router->get('/attachment/index', [Controller\Attachment::class, 'getIndex']);
    $router->get('/attachment/index-data', [Controller\Attachment::class, 'getIndexData']);
    $router->get('/attachment/detail', [Controller\Attachment::class, 'getDetail']);
    $router->post('/attachment/delete', [Controller\Attachment::class, 'postDelete']);

    // 日志
    $router->get('/operation-log/index', [Controller\OperationLog::class, 'getIndex']);
    $router->get('/operation-log/index-data', [Controller\OperationLog::class, 'getIndexData']);
    $router->get('/operation-log/detail', [Controller\OperationLog::class, 'getDetail']);
    $router->post('/operation-log/delete', [Controller\OperationLog::class, 'postDelete']);
    $router->post('/operation-log/clear', [Controller\OperationLog::class, 'postClear']);

}, [
    // 中间件
    'middleware' => config('admin.route.middleware'),
]);
