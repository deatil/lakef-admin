<?php

declare(strict_types=1);

namespace Lakef\Serverlog\Controller;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

use Lakef\Serverlog\Traits\Json as JsonTrait;
use Lakef\Serverlog\Traits\ApiCheck as ApiCheckTrait;
use Lakef\Serverlog\Event\LogsAdd as LogsAddEvent;

/**
 * 日志
 */
class Logs
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
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;
    
    /**
     * json
     */
    use JsonTrait;
    
    /**
     * Api检测
     */
    use ApiCheckTrait;
    
    /**
     * 接收日志
     */
    public function postAdd()
    {
        $check = $this->checkSign();
        if ($check !== true) {
            return $check;
        }
        
        $data = [
            'app_id' => $this->request->input('app_id'),
            'content' => serverlog_json_encode($this->request->all()),
            'add_time' => time(),
            'add_ip' => $this->request->server('remote_addr'),
        ];
        $this->eventDispatcher->dispatch(new LogsAddEvent($data));
        
        return $this->successJson("提交成功");
    }
    
}
