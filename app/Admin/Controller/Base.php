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
use Hyperf\Utils\Arr;

use App\Admin\Traits\Json as JsonTrait;
use App\Admin\Traits\View as ViewTrait;
use App\Admin\Traits\AuthAdmin as AuthAdminTrait;

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
     * json
     */
    use JsonTrait;
    
    /**
     * 视图
     */
    use ViewTrait;
    
    /**
     * 当前登陆账号
     */
    use AuthAdminTrait;
    
}
