<?php

declare (strict_types = 1);

namespace App\Admin\Traits;

/**
 * 当前登陆账号信息
 *
 * create: 2021-4-18
 * author: deatil
 */
trait AuthAdmin
{
    /**
     * 当前管理员
     */
    public function getAuthAdmin()
    {
        return $this->request->getAttribute('authAdmin');
    }
    
    /**
     * 当前管理员信息
     */
    public function getAuthAdminInfo()
    {
        return $this->getAuthAdmin()->getData();
    }
    
    /**
     * 当前管理员ID
     */
    public function getAuthAdminId()
    {
        return $this->getAuthAdmin()->getId();
    }
    
    /**
     * 管理员是否为超级管理员
     */
    public function getIsSuperAdmin()
    {
        return $this->getAuthAdmin()->isSuperAdmin();
    }
}
