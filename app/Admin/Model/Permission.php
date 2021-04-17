<?php

declare(strict_types=1);

namespace App\Admin\Model;

use Donjan\Permission\Models\Permission as PermissionModel;

/**
 * 权限
 */
class Permission extends PermissionModel
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'int', 
        'is_menu' => 'integer',
        'is_click' => 'integer',
    ];

}
