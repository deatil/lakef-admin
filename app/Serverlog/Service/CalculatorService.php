<?php

declare(strict_types=1);

namespace App\Serverlog\Service;

use Hyperf\RpcServer\Annotation\RpcService;

use App\Serverlog\Contracts\CalculatorServiceInterface;

/**
 * 注意，如希望通过服务中心来管理服务，需在注解内增加 publishTo 属性
 * @RpcService(name="CalculatorService", protocol="jsonrpc-http", server="jsonrpc-http")
 */
class CalculatorService implements CalculatorServiceInterface
{
    // 实现一个加法方法，这里简单的认为参数都是 int 类型
    public function add(int $a, int $b): int
    {
        // 这里是服务方法的具体实现
        return $a + $b + 5;
    }
}
