<?php

declare(strict_types=1);

namespace Lakef\Serverlog\Contracts;

/**
 * App
 */
interface AppInterface
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
     * 创建
     */
    public function create(array $data = []);
    
    /**
     * 更新
     */
    public function update($id, array $data = [], $makeSecret = false);
    
    /**
     * 删除
     */
    public function delete(array $param = []);
}
