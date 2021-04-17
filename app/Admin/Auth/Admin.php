<?php

declare(strict_types=1);

namespace App\Admin\Auth;

use App\Admin\Support\Tree;
use App\Admin\Model\Role as RoleModel;
use App\Admin\Model\Admin as AdminModel;

class Admin
{
    /**
     * 账号ID
     */
    protected $id = null;

    /**
     * 账号数据
     */
    protected $data = [];
    
    /**
     * 账号ID
     */
    public function withId($id)
    {
        $this->id = $id;
        
        return $this;
    }
    
    /**
     * 获取ID
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * 数据
     */
    public function withData(array $data)
    {
        $this->data = $data;
        
        return $this;
    }
    
    /**
     * 获取数据
     */
    public function getData()
    {
        if (empty($this->data)) {
            $this->data = AdminModel::query()
                ->where([
                    'id' => $this->id,
                ])
                ->first();
        }
        
        return $this->data;
    }
    
    /**
     * 是否为超级管理员
     */
    public function isSuperAdmin()
    {
        $adminId = $this->getId();
        if (empty($adminId)) {
            return false;
        }
        
        return ($adminId == config('serverlog.passport.super_id'));
    }
    
    /**
     * 是否启用
     */
    public function isActive()
    {
        $info = $this->getData();
        if (empty($info)) {
            return false;
        }
        
        return ($info['status'] == 1);
    }
    
    /**
     * 获取角色集合
     */
    public function getRoleNames()
    {
        $info = $this->getData();
        if (empty($info)) {
            return [];
        }
        
        return $info->getRoleNames()->toArray();
    }
    
    /**
     * 获取角色ID集合
     */
    public function getRoleIds()
    {
        $info = $this->getData();
        if (empty($info)) {
            return [];
        }
        
        return $info->getRoleIds()->toArray();
    }
    
    /**
     * 获取子角色ID集合
     */
    public function getChildRoleIds()
    {
        $roleIds = $this->getRoleIds();
        if (empty($roleIds)) {
            return [];
        }
        
        $roleList = RoleModel
            ::orderBy('sort', 'ASC')
            ->orderBy('id', 'ASC')
            ->get()
            ->toArray();
        
        $childRoleIds = [];
        foreach ($roleIds as $roleId) {
            $tree = new Tree();
            $treeData = $tree->withData($roleList)
                ->withConfig('parentidKey', 'parent_id')
                ->buildArray($roleId);
            $roles = $tree->buildFormatList($treeData);
            $childRoleIds = array_merge($childRoleIds, collect($roles)->pluck('id')->toArray());
            unset($tree);
        }
        
        if (! empty($childRoleIds)) {
            $childRoleIds = array_values($childRoleIds);
        }
        
        return $childRoleIds;
    }
    
    /**
     * 获取所有权限
     */
    public function getPermissions()
    {
        $info = $this->getData();
        if (empty($info)) {
            return [];
        }
        
        return $info->permissions->toArray();
    }
    
    /**
     * 获取所有权限ID列表
     */
    public function getPermissionIds()
    {
        $info = $this->getData();
        if (empty($info)) {
            return [];
        }
        
        return $info->permissions->pluck('id')->toArray();
    }
}
