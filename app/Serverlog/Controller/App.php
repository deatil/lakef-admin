<?php

declare (strict_types = 1);

namespace App\Serverlog\Controller;

use App\Admin\Model\Attachment as AttachmentModel;

/**
 * app
 *
 * create: 2021-4-19
 * author: deatil
 */
class App extends Base
{
    /**
     * 列表
     */
    public function getIndex()
    {
        return $this->view('admin::attachment.index');
    }
    
    /**
     * 列表
     */
    public function getIndexData()
    {
        $page = (int) $this->request->input('page', 1);
        $limit = (int) $this->request->input('limit', 10);
        
        $where = [];
        
        $name = $this->request->input('name');
        if (! empty($name)) {
            $where[] = ['name', 'like', '%'.$name.'%'];
        }
        
        $ext = $this->request->input('ext');
        if (! empty($ext)) {
            $where[] = ['ext', 'like', '%'.$ext.'%'];
        }
        
        $page = max($page, 1);
        
        $list = AttachmentModel::query()
            ->where($where)
            ->offset($page - 1)
            ->limit($limit)
            ->get();
            
        $count = AttachmentModel::query()
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
            return $this->error('文件ID不能为空');
        }
        
        $fileInfo = AttachmentModel::where(['id' => $id])
            ->first();
        if (empty($fileInfo)) {
            return $this->error('文件信息不存在');
        }
        
        return $this->view('admin::attachment.detail', [
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
            return $this->errorJson('文件ID不能为空');
        }
        
        $fileInfo = AttachmentModel::where(['id' => $id])
            ->first();
        if (empty($fileInfo)) {
            return $this->errorJson('文件信息不存在');
        }
        
        $deleteStatus = AttachmentModel::where([
                'id' => $id
            ])
            ->delete();
        if ($deleteStatus === false) {
            return $this->errorJson('文件删除失败');
        }
        
        return $this->successJson('文件删除成功');
    }
}
