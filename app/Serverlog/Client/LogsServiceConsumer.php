<?php

declare(strict_types=1);

namespace App\Serverlog\Client;

use Hyperf\RpcClient\AbstractServiceClient;

class LogsServiceConsumer extends AbstractServiceClient 
{
    /**
     * 定义对应服务提供者的服务名称
     * @var string 
     */
    protected $serviceName = 'LogsService';
    
    /**
     * 定义对应服务提供者的服务协议
     * @var string 
     */
    protected $protocol = 'jsonrpc-http';

    public function dataList(array $param = []): array
    {
        return $this->__request(__FUNCTION__, compact('param'));
    }

    public function dataCount(array $param = [])
    {
        return $this->__request(__FUNCTION__, compact('param'));
    }

    public function detail(array $param = [])
    {
        return $this->__request(__FUNCTION__, compact('param'));
    }

    public function delete(array $param = [])
    {
        return $this->__request(__FUNCTION__, compact('param'));
    }
}
