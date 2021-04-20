<?php

declare (strict_types = 1);

namespace App\Admin\Controller\Serverlog;

use App\Admin\Controller\Base;
use App\Serverlog\Client\LogsServiceConsumer;
use App\Serverlog\Client\AppServiceConsumer;

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
        return $this->view('admin::server-log.logs.index');
    }
    
    /**
     * 列表
     */
    public function getIndexData()
    {
        $page = (int) $this->request->input('page', 1);
        $limit = (int) $this->request->input('limit', 10);
        
        $where = [];
        
        $appId = $this->request->input('app_id');
        if (! empty($appId)) {
            $where[] = ['app_id', 'like', '%'.$appId.'%'];
        }
        
        $content = $this->request->input('content');
        if (! empty($content)) {
            $where[] = ['content', 'like', '%'.$content.'%'];
        }
        
        $page = max($page, 1);
        $offset = ($page - 1) * $limit;
        $limit = max($limit, 1);
        
        $param = [
            'offset' => $offset,
            'limit' => $limit,
            'where' => $where,
            'sort' => [
                ['created_at', 'DESC'],
            ],
        ];
        
        $logsModel = di(LogsServiceConsumer::class);
        
        $list = $logsModel->dataList($param);
        $count = $logsModel->dataCount($param);
        
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
        
        $logsModel = di(LogsServiceConsumer::class);
        $info = $logsModel->detail([
            'where' => [
                ['id', '=', $id]
            ],
        ]);
        if (empty($info)) {
            return $this->error('日志信息不存在');
        }
        
        $appModel = di(AppServiceConsumer::class);
        $appInfo = $appModel->detail([
            'where' => [
                ['app_id', '=', $info['app_id']]
            ],
        ]);
        
        return $this->view('admin::server-log.logs.detail', [
            'app' => $appInfo,
            'info' => $info,
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
        
        $logsModel = di(LogsServiceConsumer::class);
        
        $deleteStatus = $logsModel->delete([
            'where' => [
                ['id', 'in', $id],
            ],
        ]);
        if ($deleteStatus === false) {
            return $this->errorJson('日志删除失败');
        }
        
        return $this->successJson('日志删除成功');
    }
    
    /**
     * 清空
     */
    public function postClear()
    {
        $logsModel = di(LogsServiceConsumer::class);
        
        $deleteStatus = $logsModel->delete([
            'where' => [
                ['add_time', '<=', time() - (86400 * 30)],
            ],
        ]);
        if ($deleteStatus === false) {
            return $this->errorJson('清空日志失败');
        }
        
        return $this->successJson('清空日志成功');
    }
}
