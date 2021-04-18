<?php

declare(strict_types=1);

namespace App\Serverlog\Client;

use Hyperf\RpcClient\AbstractServiceClient;

use App\Serverlog\Contracts\CalculatorServiceInterface;

class CalculatorServiceConsumer 
    extends AbstractServiceClient 
    implements CalculatorServiceInterface
{
    /**
     * 定义对应服务提供者的服务名称
     * @var string 
     */
    protected $serviceName = 'CalculatorService';
    
    /**
     * 定义对应服务提供者的服务协议
     * @var string 
     */
    protected $protocol = 'jsonrpc-http';

    public function add(int $a, int $b): int
    {
        return $this->__request(__FUNCTION__, compact('a', 'b'));
    }
}
