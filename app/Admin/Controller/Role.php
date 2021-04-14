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
        return $this->view('admin.role.index');
    }
    
    /**
     * 首页
     */
    public function postIndex()
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
            ->offset($page)
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
        return $this->view('admin.role.create');
    }
    
    /**
     * 创建
     */
    public function postCreate()
    {
        return $this->successJson('创建成功');
    }
    
    /**
     * 更新
     */
    public function getUpdate()
    {
        return $this->view('admin.role.update');
    }
    
    /**
     * 更新
     */
    public function postUpdate()
    {
        return $this->successJson('更新成功');
    }
    
    /**
     * 删除
     */
    public function postDelete()
    {
        return $this->successJson('删除成功');
    }
}
