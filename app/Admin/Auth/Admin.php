<?php

declare(strict_types=1);

namespace App\Admin\Auth;

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
}
