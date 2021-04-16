<?php

declare(strict_types=1);

namespace App\Admin\Model;

use Donjan\Permission\Traits\HasRoles;

use Hyperf\DbConnection\Model\Model;

/**
 * 管理员
 */
class Admin extends Model
{
    use HasRoles;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'salt', 
        'remark', 
        'status', 
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'int', 
        'status' => 'integer', 
        'created_at' => 'datetime', 
        'updated_at' => 'datetime'
    ];

    public function getRoleIds()
    {
        return $this->roles->pluck('id');
    }

    public static function getIntersectRoles(array $oldRoles = [], array $newRoles = [])
    {
        $intersectRoles = array_intersect_assoc($oldRoles, $newRoles);
        return $intersectRoles;
    }
}
