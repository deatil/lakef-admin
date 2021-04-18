<?php

declare(strict_types=1);

namespace App\Admin\Model;

use Hyperf\Filesystem\FilesystemFactory;
use Hyperf\DbConnection\Model\Model;

use App\Admin\Support\Upload;

/**
 * 附件
 */
class Attachment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attachment';
    
    protected $keyType = 'string';
    
    protected $primaryKey = 'id';
    
    protected $casts = [
        'id' => 'string', 
    ];
    
    public $timestamps = false;
    
    protected $guarded = [];
    
    protected $appends = [
        'sizes',
        'uri',
        'realpath',
    ];
    
    public function getSizesAttribute()
    {
        $bytes = $this->size;
        $sizeText = [" B", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB"];
        return round($bytes / pow(1024, ($i = floor(log(floatval($bytes), 1024)))), 2) . $sizeText[$i];
    }

    public function getUriAttribute()
    {
        return di(Upload::class)->objectUrl($this->path, $this->driver);
    }

    public function getRealpathAttribute()
    {
        return di(Upload::class)->objectPath($this->path, $this->driver);
    }
    
}
