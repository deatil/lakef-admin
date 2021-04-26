<?php

declare(strict_types=1);

namespace Lakef\Serverlog\Event;

/**
 * 日志数据
 */
class LogsAdd
{
    public $data;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
}
