<?php

declare(strict_types=1);

namespace App\Serverlog\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * App
 */
class App extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'serverlog_app';
    
    protected $keyType = 'string';
    
    protected $primaryKey = 'id';
    
    protected $casts = [
        'id' => 'string', 
    ];
    
    protected $guarded = [];
    
    public function logs()
    {
        return $this->hasMany(Logs::class, 'app_id', 'id');
    }
    
}
