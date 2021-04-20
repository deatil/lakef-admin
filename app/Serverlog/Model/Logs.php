<?php

declare(strict_types=1);

namespace App\Serverlog\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * 日志记录
 */
class Logs extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'serverlog_log';
    
    protected $keyType = 'string';
    
    protected $primaryKey = 'id';
    
    protected $casts = [
        'id' => 'string', 
    ];
    
    protected $guarded = [];
    
    public function app()
    {
        return $this->belongsTo(App::class, 'app_id', 'id');
    }

    /**
     * 记录日志
     */
    public static function record(array $data = [])
    {
        $data = array_merge([
            'id' => serverlog_rand_id(),
        ], $data);
        
        return self::create($data);
    }
    
}
