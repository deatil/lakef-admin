<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use Psr\Container\ContainerInterface;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\SessionInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\View\RenderInterface;

abstract class Base
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @Inject
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @Inject
     * @var RenderInterface
     */
    protected $view;
    
    /**
     * @Inject()
     * @var SessionInterface
     */
    protected $session;

    /**
     * @Inject
     * @var ValidatorFactoryInterface
     */
    protected $validationFactory;
    
    /**
     * 视图
     */
    protected function view(string $path, array $data = [])
    {
        return $this->view->render($path, $data);
    }
    
    /**
     * json
     */
    protected function json(
        $success = true, 
        $code = 0, 
        $message = "", 
        $data = []
    ) {
        $result = [];
        $result['success'] = $success;
        $result['code'] = $code;
        $message ? $result['message'] = $message : null;
        $data ? $result['data'] = $data : null;
        
        return $this->response->json($result);
    }
    
    /**
     * 返回错误json
     */
    protected function errorJson(
        $msg = null, 
        $code = 1, 
        $data = []
    ) {
        return $this->json(false, $code, $msg, $data);
    }
    
    /**
     * 返回成功json
     */
    protected function successJson(
        $msg = null, 
        $data = [], 
        $code = 0
    ) {
        return $this->json(true, $code, $msg, $data);
    }
}
