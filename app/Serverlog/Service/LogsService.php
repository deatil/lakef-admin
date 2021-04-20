<?php

declare(strict_types=1);

namespace App\Serverlog\Service;

use Hyperf\Utils\Arr;
use Hyperf\RpcServer\Annotation\RpcService;

use App\Serverlog\Contracts\LogsInterface;
use App\Serverlog\Model\Logs as LogsModel;

/**
 * 注意，如希望通过服务中心来管理服务，需在注解内增加 publishTo 属性
 * @RpcService(name="LogsService", protocol="jsonrpc-http", server="jsonrpc-http")
 */
class LogsService implements LogsInterface
{
    /**
     * 数据列表
     */
    public function dataList(array $param = [])
    {
        $offset = (int) Arr::get($param, 'offset', 0);
        $limit = (int) Arr::get($param, 'limit', 10);
        
        $where = (array) Arr::get($param, 'where', []);
        $orWhere = (array) Arr::get($param, 'orWhere', []);
        
        $sorts = (array) Arr::get($param, 'sort', []);
        
        $list = LogsModel::query()
            ->wheres($where)
            ->orWheres($orWhere)
            ->offset($offset)
            ->limit($limit);
        
        foreach ($sorts as $sort) {
            if (!empty($sort[0]) && !empty($sort[1])) {
                $list->orderBy($sort[0], $sort[1]);
            }
        }
        
        return $list->get();
    }
    
    /**
     * 数据总数
     */
    public function dataCount(array $param = [])
    {
        $where = (array) Arr::get($param, 'where', []);
        $orWhere = (array) Arr::get($param, 'orWhere', []);
        
        $count = LogsModel::query()
            ->wheres($where)
            ->orWheres($orWhere)
            ->count();
        
        return (int) $count;
    }
    
    /**
     * 详情
     */
    public function detail(array $param = [])
    {
        $where = (array) Arr::get($param, 'where', []);
        
        $info = LogsModel
            ::wheres($where)
            ->first();
        
        return $info;
    }
    
    /**
     * 删除
     */
    public function delete(array $param = [])
    {
        $where = (array) Arr::get($param, 'where', []);
        $orWhere = (array) Arr::get($param, 'orWhere', []);
        
        $delete = LogsModel
            ::wheres($where)
            ->orWheres($orWhere)
            ->delete();
        if ($delete === false) {
            return false;
        }
        
        return true;
    }
}
