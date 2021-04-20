<?php

declare (strict_types = 1);

namespace App\Admin\Controller\Serverlog;

use App\Serverlog\Client\AppServiceConsumer;
use App\Admin\Controller\Base;

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
        return $this->view('admin::server-log.app.index');
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
        
        $appId = $this->request->input('app_id');
        if (! empty($appId)) {
            $where[] = ['app_id', 'like', '%'.$appId.'%'];
        }
        
        $page = max($page, 1);
        $offset = ($page - 1) * $limit;
        $limit = max($limit, 1);
        
        $param = [
            'offset' => $offset,
            'limit' => $limit,
            'where' => $where,
            'sort' => [
                ['created_at', 'ASC'],
            ],
        ];
        
        $appModel = di(AppServiceConsumer::class);
        
        $list = $appModel->dataList($param);
        $count = $appModel->dataCount($param);
        
        return $this->tableJson($list, $count);
    }
    
    /**
     * 创建
     */
    public function getCreate()
    {
        return $this->view('admin::server-log.app.create');
    }
    
    /**
     * 创建
     */
    public function postCreate()
    {
        $validator = $this->validationFactory->make(
            $this->request->all(),
            [
                'name' => 'required',
            ],
            [
                'name.required' => '应用名称必填',
            ]
        );
        if ($validator->fails()) {
            return $this->errorJson($validator->errors()->first());
        }
        
        $name = $this->request->post('name');
        $description = $this->request->post('description');
        
        $allowOrigin = $this->request->post('allow_origin');
        if (! empty($allowOrigin)) {
            $allowOrigin = 1;
        } else {
            $allowOrigin = 0;
        }
        
        $isCheck = $this->request->post('is_check');
        if (! empty($isCheck)) {
            $isCheck = 1;
        } else {
            $isCheck = 0;
        }
        
        $sort = $this->request->post('sort', 100);
        
        $status = $this->request->post('status');
        if (! empty($status)) {
            $status = 1;
        } else {
            $status = 0;
        }
        
        $appModel = di(AppServiceConsumer::class);
        
        $app = $appModel->create([
            'name' => $name,
            'description' => $description,
            'allow_origin' => $allowOrigin,
            'is_check' => $isCheck,
            'sort' => $sort,
            'status' => $status,
        ]);
        if ($app === false) {
            return $this->errorJson('应用创建失败');
        }
        
        return $this->successJson('应用创建成功');
    }
    
    /**
     * 更新
     */
    public function getUpdate()
    {
        $id = $this->request->input('id');
        if (empty($id)) {
            return $this->error('ID不能为空');
        }
        
        $appModel = di(AppServiceConsumer::class);
        $info = $appModel->detail([
            'where' => [
                ['id', '=', $id]
            ],
        ]);
        if (empty($info)) {
            return $this->error('应用信息不存在');
        }
        
        return $this->view('admin::server-log.app.update', [
            'info' => $info,
        ]);
    }
    
    /**
     * 更新
     */
    public function postUpdate()
    {
        $id = $this->request->input('id');
        if (empty($id)) {
            return $this->errorJson('应用ID不能为空');
        }
        
        $appModel = di(AppServiceConsumer::class);
        
        $info = $appModel->detail([
            'where' => [
                ['id', '=', $id]
            ],
        ]);
        if (empty($info)) {
            return $this->errorJson('应用信息不存在');
        }
        
        $validator = $this->validationFactory->make(
            $this->request->all(),
            [
                'name' => 'required',
            ],
            [
                'name.required' => '应用名称必填',
            ]
        );
        if ($validator->fails()) {
            return $this->errorJson($validator->errors()->first());
        }
        
        $name = $this->request->post('name');
        $description = $this->request->post('description');
        
        $allowOrigin = $this->request->post('allow_origin');
        if (! empty($allowOrigin)) {
            $allowOrigin = 1;
        } else {
            $allowOrigin = 0;
        }
        
        $isCheck = $this->request->post('is_check');
        if (! empty($isCheck)) {
            $isCheck = 1;
        } else {
            $isCheck = 0;
        }
        
        $sort = $this->request->post('sort', 100);
        
        $status = $this->request->post('status');
        if (! empty($status)) {
            $status = 1;
        } else {
            $status = 0;
        }
        
        // 生成 appSecret 
        $makeSecret = $this->request->post('make_secret');
        if (! empty($makeSecret)) {
            $makeSecret = true;
        } else {
            $makeSecret = false;
        }
        
        $update = $appModel->update($id, [
            'name' => $name,
            'description' => $description,
            'allow_origin' => $allowOrigin,
            'is_check' => $isCheck,
            'sort' => $sort,
            'status' => $status,
        ], $makeSecret);
        if ($update === false) {
            return $this->errorJson('应用更新失败');
        }
        
        return $this->successJson('应用更新成功');
    }
    
    /**
     * 详情
     */
    public function getDetail()
    {
        $id = $this->request->input('id');
        if (empty($id)) {
            return $this->error('应用ID不能为空');
        }
        
        $appModel = di(AppServiceConsumer::class);
        
        $info = $appModel->detail([
            'where' => [
                ['id', '=', $id]
            ],
        ]);
        if (empty($info)) {
            return $this->error('应用信息不存在');
        }
        
        return $this->view('admin::server-log.app.detail', [
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
            return $this->errorJson('应用ID不能为空');
        }
        
        $appModel = di(AppServiceConsumer::class);
        
        $info = $appModel->detail([
            'where' => [
                ['id', '=', $id]
            ],
        ]);
        if (empty($info)) {
            return $this->errorJson('应用信息不存在');
        }
        
        $deleteStatus = $appModel->delete(['id' => $id]);
        if ($deleteStatus === false) {
            return $this->errorJson('应用删除失败');
        }
        
        return $this->successJson('应用删除成功');
    }
}
