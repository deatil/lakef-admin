<?php

declare(strict_types=1);

namespace Lakef\Admin\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * 操作日志
 */
class OperationLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'operation_log';
    
    protected $keyType = 'string';
    
    protected $primaryKey = 'id';
    
    protected $casts = [
        'id' => 'string', 
    ];
    
    protected $guarded = [];
    
    public $timestamps = false;

    /**
     * 记录日志
     */
    public static function record(array $data = [])
    {
        $authAdmin = context_request()->getAttribute('authAdmin');
        if (! empty($authAdmin)) {
            $admimData = $authAdmin->getData();
        } else {
            $admimData = [
                'id' => 0,
                'name' => '',
            ];
        }
        
        $data = array_merge([
            'id' => admin_md5(microtime().mt_rand(10000, 99999)),
            'admin_id' => $admimData['id'],
            'admin_name' => $admimData['name'],
            'method' => request()->getMethod(),
            'url' => request()->fullUrl(),
            'info' => admin_json_encode(request()->all()),
            'ip' => request()->server('remote_addr'),
            'useragent' => request()->server('HTTP_USER_AGENT'),
            'create_time' => time(),
        ], $data);
        
        return self::create($data);
    }

}
