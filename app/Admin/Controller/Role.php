<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use Donjan\Permission\Models\Role as RoleModel;

/**
 * 角色
 */
class Role extends Base
{
    /**
     * 首页
     */
    public function getIndex()
    {
        return $this->view('serverlog::role.index');
    }
    
    /**
     * 首页
     */
    public function getData()
    {
        $page = (int) $this->request->input('page', 1);
        $limit = (int) $this->request->input('limit', 10);
        
        $where = [];
        
        $name = $this->request->input('name');
        if (! empty($name)) {
            $where[] = ['name', 'like', '%'.$name.'%'];
        }
        
        $guardName = $this->request->input('guard_name');
        if (! empty($guardName)) {
            $where[] = ['guard_name', 'like', '%'.$guardName.'%'];
        }
        
        $page = max($page, 1);
        $list = RoleModel::where($where)
            ->offset($page - 1)
            ->limit($limit)
            ->get();
            
        $count = RoleModel::where($where)
            ->count();
        
        return $this->tableJson($list, $count);
    }
    
    /**
     * 创建
     */
    public function getCreate()
    {
        return $this->view('serverlog::role.create');
    }
    
    /**
     * 创建
     */
    public function postCreate()
    {
        $validator = $this->validationFactory->make(
            $this->request->all(),
            [
                'name' => 'required|min:1',
                'guard_name' => 'required|min:2',
            ],
            [
                'name.required' => '角色名必填',
                'name.min' => '角色名最少1位',
                'guard_name.required' => '守护类型必填',
                'guard_name.min' => '守护类型最少2位',
            ]
        );
        if ($validator->fails()) {
            return $this->errorJson($validator->errors()->first());
        }
        
        $name = $this->request->post('name');
        $guardName = $this->request->post('guard_name');
        $description = $this->request->post('description');
        
        $info = RoleModel::query()
            ->where([
                'name' => $name,
            ])
            ->first();
        if (! empty($info)) {
            return $this->errorJson('角色名已经存在');
        }
        
        $role = RoleModel::create([
            'name' => $name,
            'guard_name' => $guardName,
            'description' => $description,
        ]);
        if ($role === false) {
            return $this->errorJson('角色创建失败');
        }
        
        return $this->successJson('角色创建成功');
    }
    
    /**
     * 更新
     */
    public function getUpdate()
    {
        $id = (int) $this->request->input('id');
        if (empty($id)) {
            return $this->error('ID不能为空');
        }
        
        $info = RoleModel::query()
            ->where([
                'id' => $id,
            ])
            ->first();
        if (empty($info)) {
            return $this->error('角色信息不存在');
        }
        
        return $this->view('serverlog::role.update', [
            'info' => $info,
        ]);
    }
    
    /**
     * 更新
     */
    public function postUpdate()
    {
        $id = (int) $this->request->input('id');
        if (empty($id)) {
            return $this->errorJson('ID不能为空');
        }
        
        $info = RoleModel::query()
            ->where([
                'id' => $id,
            ])
            ->first();
        if (empty($info)) {
            return $this->errorJson('角色信息不存在');
        }
        
        $validator = $this->validationFactory->make(
            $this->request->all(),
            [
                'name' => 'required|min:1',
                'guard_name' => 'required|min:2',
            ],
            [
                'name.required' => '角色名必填',
                'name.min' => '角色名最少1位',
                'guard_name.required' => '守护类型必填',
                'guard_name.min' => '守护类型最少2位',
            ]
        );
        if ($validator->fails()) {
            return $this->errorJson($validator->errors()->first());
        }
        
        $name = $this->request->post('name');
        $guardName = $this->request->post('guard_name');
        $description = $this->request->post('description');
        
        $info = RoleModel::query()
            ->where([
                'name' => $name,
            ])
            ->first();
        if (! empty($info) && $info['id'] != $id) {
            return $this->errorJson('角色名已经存在');
        }
        
        $update = RoleModel::where([
            ['id', '=', $id],
        ])->update([
            'name' => $name,
            'guard_name' => $guardName,
            'description' => $description,
        ]);
        if ($update === false) {
            return $this->errorJson('更新失败');
        }
        
        return $this->successJson('更新成功');
    }
    
    /**
     * 删除
     */
    public function postDelete()
    {
        $id = $this->request->post('id');
        if (empty($id)) {
            return $this->errorJson('ID不能为空');
        }
        
        if (! is_array($id)) {
            $id = [$id];
        }
        
        $delete = RoleModel::whereIn('id', $id)
            ->delete();
        if ($delete === false) {
            return $this->errorJson('删除失败');
        }
        
        return $this->successJson('删除成功');
    }
}
