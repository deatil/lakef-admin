<?php

declare(strict_types=1);

namespace App\Serverlog\Controller;

use App\Admin\Controller\Base as BaseController;
use App\Serverlog\Client\AppServiceConsumer;
use App\Serverlog\Client\LogsServiceConsumer;

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
        $client = di(LogsServiceConsumer::class);

        $result = $client->dataCount();
        
        return $result;
    }
    
}
