<?php

declare(strict_types=1);

namespace Lakef\Admin\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Str;
use Hyperf\Utils\Context;
use Hyperf\HttpServer\Contract\RequestInterface;

use Lakef\Admin\Model\OperationLog as OperationLogModel;

/**
 * 操作日志记录
 */
class OperationLog implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request, 
        RequestHandlerInterface $handler
    ): PsrResponseInterface 
    {
        $response = $handler->handle($request);
        
        if (Str::startsWith('/'.request()->decodedPath(), config('admin.route.group'))) {
            $info = request()->all();
            
            // 过滤密码相关
            Arr::forget($info, [
                'password',
                'password_confirm',
                'old_password',
                'new_password',
                'again_password',
            ]);
            
            OperationLogModel::record([
                'info' => admin_json_encode($info),
            ]);
        }
        
        return $response;
    }
    
}
