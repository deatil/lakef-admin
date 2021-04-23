<?php

declare(strict_types=1);

namespace Lakef\Serverlog\Service;

use Hyperf\Utils\Arr;
use Hyperf\RpcServer\Annotation\RpcService;

use Lakef\Serverlog\Contracts\AppInterface;
use Lakef\Serverlog\Model\App as AppModel;

/**
 * 注意，如希望通过服务中心来管理服务，需在注解内增加 publishTo 属性
 * @RpcService(name="AppService", protocol="jsonrpc-http", server="jsonrpc-http")
 */
class AppService implements AppInterface
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
        
        $list = AppModel::query()
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
        
        $count = AppModel::query()
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
        
        $info = AppModel
            ::wheres($where)
            ->first();
        
        return $info;
    }
    
    /**
     * 创建
     */
    public function create(array $data = [])
    {
        $appId = config('serverlog.app.prefix').date('YmdHis').mt_rand(1000, 9999);
        $defaultData = [
            'id' => serverlog_rand_id(),
            'app_id' => $appId,
            'app_secret' => serverlog_secret_id(),
        ];
        
        $data = array_merge($defaultData, $data);
        
        $create = AppModel::create($data);
        if ($create === false) {
            return false;
        }
        
        return true;
    }
    
    /**
     * 更新
     */
    public function update($id, array $data = [], $makeSecret = false)
    {
        if (empty($id)) {
            return false;
        }
        
        $defaultData = [];
        if ($makeSecret) {
            $defaultData['app_secret'] = serverlog_secret_id();
        }
        
        $data = array_merge($defaultData, $data);
        
        // 移除对 app_id 的修改
        Arr::forget($data, [
            'app_id',
        ]);
        
        $update = AppModel
            ::where('id', $id)
            ->update($data);
        if ($update === false) {
            return false;
        }

        return $id;
    }
    
    /**
     * 删除
     */
    public function delete(array $param = [])
    {
        $where = (array) Arr::get($param, 'where', []);
        $orWhere = (array) Arr::get($param, 'orWhere', []);
        
        $delete = AppModel
            ::wheres($where)
            ->orWheres($orWhere)
            ->delete();
        if ($delete === false) {
            return false;
        }
        
        return true;
    }
}
