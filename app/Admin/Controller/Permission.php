<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use Donjan\Permission\Models\Permission as PermissionModel;

/**
 * 权限
 */
class Permission extends Base
{
    /**
     * 首页
     */
    public function getIndex()
    {
        $authAdmin = $this->request->getAttribute('authAdmin');
        return $this->view('admin.permission.index', [
            'admin' => $authAdmin,
        ]);
    }
    
    /**
     * 创建
     */
    public function getCreate()
    {
        return $this->view('admin.permission.create');
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
        return $this->view('admin.permission.update');
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
