<?php

declare(strict_types=1);

namespace App\Serverlog\Contracts;

/**
 * 日志
 */
interface LogsInterface
{
    /**
     * 数据列表
     */
    public function dataList(array $param = []);
    
    /**
     * 数据总数
     */
    public function dataCount(array $param = []);
    
    /**
     * 详情
     */
    public function detail(array $param = []);
    
    /**
     * 删除
     */
    public function delete(array $param = []);
}
