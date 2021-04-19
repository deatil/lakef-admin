<?php

declare (strict_types = 1);

namespace App\Admin\Controller;

use App\Admin\Model\OperationLog as OperationLogModel;

/**
 * 日志
 *
 * create: 2021-4-19
 * author: deatil
 */
class Logs extends Base
{
    /**
     * 列表
     */
    public function getIndex()
    {
        return $this->view('admin::operation-log.index');
    }
    
    /**
     * 列表
     */
    public function getIndexData()
    {
        $page = (int) $this->request->input('page', 1);
        $limit = (int) $this->request->input('limit', 10);
        
        $where = [];
        
        $adminName = $this->request->input('admin_name');
        if (! empty($adminName)) {
            $where[] = ['admin_name', 'like', '%'.$adminName.'%'];
        }
        
        $method = $this->request->input('method');
        if (! empty($method)) {
            $where[] = ['method', '=', $method];
        }
        
        $url = $this->request->input('url');
        if (! empty($url)) {
            $where[] = ['url', 'like', '%'.$url.'%'];
        }
        
        $info = $this->request->input('info');
        if (! empty($info)) {
            $where[] = ['info', 'like', '%'.$info.'%'];
        }
        
        $useragent = $this->request->input('useragent');
        if (! empty($useragent)) {
            $where[] = ['useragent', 'like', '%'.$useragent.'%'];
        }
        
        $ip = $this->request->input('ip');
        if (! empty($ip)) {
            $where[] = ['ip', 'like', '%'.$ip.'%'];
        }
        
        $page = max($page, 1);
        
        $list = OperationLogModel::query()
            ->where($where)
            ->orderBy('create_time', 'DESC')
            ->offset($page - 1)
            ->limit($limit)
            ->get();
            
        $count = OperationLogModel::query()
            ->where($where)
            ->count();
        
        return $this->tableJson($list, $count);
    }
    
    /**
     * 详情
     */
    public function getDetail()
    {
        $id = $this->request->input('id');
        if (empty($id)) {
            return $this->error('日志ID不能为空');
        }
        
        $fileInfo = OperationLogModel::where(['id' => $id])
            ->first();
        if (empty($fileInfo)) {
            return $this->error('日志信息不存在');
        }
        
        return $this->view('admin::operation-log.detail', [
            'data' => $fileInfo,
        ]);
    }
    
    /**
     * 删除
     */
    public function postDelete()
    {
        $id = $this->request->input('id');
        if (empty($id)) {
            return $this->errorJson('日志ID不能为空');
        }
        
        if (! is_array($id)) {
            $id = [$id];
        }
        
        $deleteStatus = OperationLogModel::whereIn('id', $id)
            ->delete();
        if ($deleteStatus === false) {
            return $this->errorJson('日志删除失败');
        }
        
        return $this->successJson('日志删除成功');
    }
    
    /**
     * 清空几天前数据
     */
    public function postClear()
    {
        $deleteStatus = OperationLogModel
            ::where('create_time', '<=', time() - (86400 * 30))
            ->delete();
        if ($deleteStatus === false) {
            return $this->errorJson('日志清空失败');
        }
        
        return $this->successJson('日志清空成功');
    }
}
