<?php

declare(strict_types=1);

namespace App\Admin\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Hyperf\Utils\Context;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\SessionInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\View\RenderInterface;

use App\Admin\Auth\Admin as AuthAdmin;

class Auth implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @var RenderInterface
     */
    protected $view;

    public function __construct(
        ContainerInterface $container, 
        SessionInterface $session,
        ConfigInterface $config,
        RenderInterface $view,
        RequestInterface $request,
        ResponseInterface $response
    ) {
        $this->container = $container;
        $this->session = $session;
        $this->config = $config;
        $this->view = $view;
        $this->request = $request;
        $this->response = $response;
    }

    public function process(
        ServerRequestInterface $request, 
        RequestHandlerInterface $handler
    ): PsrResponseInterface 
    {
        if (! $this->shouldPassThrough($this->request)) {
            $adminid = $this->session->get('adminid');
            if (empty($adminid)) {
                return $this->response->redirect(admin_url('passport/login'));
            }
            
            $authAdmin = make(AuthAdmin::class)->withId($adminid);
            if (! $authAdmin->isActive()) {
                if ($this->request->isMethod('get')) {
                    return $this->view->render('serverlog::view.no-permission', [
                        'message' => '账号不存在或者已被锁定',
                    ]);
                } else {
                    return $this->response->json([
                        'code' => 1,
                        'message' => '账号不存在或者已被锁定',
                    ]);
                }
            }
            
            $request = Context::get(ServerRequestInterface::class);
            $request = $request->withAttribute('authAdmin', $authAdmin);
            Context::set(ServerRequestInterface::class, $request);
        }
        
        return $handler->handle($request);
    }
    
    protected function shouldPassThrough($request)
    {
        $excepts = array_merge($this->config->get('admin.auth.authenticate_excepts', []), [
            ltrim(admin_url('passport/captcha'), '/'),
            ltrim(admin_url('passport/login'), '/'),
        ]);
        
        return $request->is($excepts);
    }
    
}
