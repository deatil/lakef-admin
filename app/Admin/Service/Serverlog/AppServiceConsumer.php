<?php

declare(strict_types=1);

namespace App\Admin\Service\Serverlog;

use Hyperf\RpcClient\AbstractServiceClient;

/**
 * app服务消费
 *
 * create: 2021-4-20
 * author: deatil
 */
class AppServiceConsumer extends AbstractServiceClient 
{
    /**
     * 定义对应服务提供者的服务名称
     * @var string 
     */
    protected $serviceName = 'AppService';
    
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

    public function create(array $data = [])
    {
        return $this->__request(__FUNCTION__, compact('data'));
    }

    public function update($id, array $data = [], $makeSecret = false)
    {
        return $this->__request(__FUNCTION__, compact('id', 'data', 'makeSecret'));
    }

    public function delete(array $param = [])
    {
        return $this->__request(__FUNCTION__, compact('param'));
    }
}
