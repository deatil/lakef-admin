<?php

declare(strict_types=1);

namespace App\Serverlog\Controller;

use App\Admin\Controller\Base as BaseController;
use App\Serverlog\Contracts\CalculatorServiceInterface;

/**
 * 首页
 */
class Index extends BaseController
{
    /**
     * 首页
     */
    public function getIndex()
    {
        $client = di(CalculatorServiceInterface::class);

        $result = $client->add(1, 2);
        
        return $result;
    }
    
}
