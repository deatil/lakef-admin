<?php

declare(strict_types=1);

namespace Lakef\Serverlog\Controller;

use Hyperf\Di\Annotation\Inject;
use Psr\Container\ContainerInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

use Lakef\Serverlog\Traits\Json as JsonTrait;
use Lakef\Serverlog\Traits\ApiCheck as ApiCheckTrait;
use Lakef\Serverlog\Model\Logs as LogsModel;

/**
 * 首页
 */
class Index
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
        
        $appId = $this->request->input('app_id');
        LogsModel::record([
            'app_id' => $appId,
            'content' => serverlog_json_encode($this->request->all()),
            'add_time' => time(),
            'add_ip' => $this->request->server('remote_addr'),
        ]);
        
        return $this->successJson("提交成功");
    }
    
}
