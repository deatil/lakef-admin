<?php

declare(strict_types=1);

namespace App\Admin\Support;

use Hyperf\Utils\Arr;
use Hyperf\Filesystem\FilesystemFactory;

use App\Admin\Model\Attachment as AttachmentModel;

/**
 * 上传
 */
class Upload
{
    protected $fileTypes = [
        'image'  => '/^(gif|png|jpe?g|svg|webp)$/i',
        'html'   => '/^(htm|html)$/i',
        'office' => '/^(docx?|xlsx?|pptx?|pps|potx?)$/i',
        'docs'  => '/^(docx?|xlsx?|pptx?|pps|potx?|rtf|ods|odt|pages|ai|dxf|ttf|tiff?|wmf|e?ps)$/i',
        'text'   => '/^(txt|md|csv|nfo|ini|json|php|js|css|ts|sql)$/i',
        'video'  => '/^(og?|mp4|webm|mp?g|mov|3gp)$/i',
        'audio'  => '/^(og?|mp3|mp?g|wav)$/i',
        'pdf'    => '/^(pdf)$/i',
        'flash'  => '/^(swf)$/i',
    ];
    
    public function getAttachmentUrl($id, $domain = false)
    {
        $data = AttachmentModel::where([
                'id' => $id
            ])
            ->first();
        $path = $data['uri'] ?? '';
        
        $domainUrl = '';
        return ($path !== false) ? 
            ($domain ? $domainUrl . $path : $path)
            : "";
    }
    
    public function deleteFile($path, $disk = '')
    {
        return $this->filesystem($disk)->delete($path);
    }
    
    public function getFilesystemDefaultDisk()
    {
        return config('admin.upload.disk');
    }
    
    public function filesystem($disk = '')
    {
        if (empty($disk)) {
            $disk = $this->getFilesystemDefaultDisk();
        }
        
        $filesystem = di()->get(FilesystemFactory::class);
        
        return $filesystem->get($disk);
    }
    
    public function writeContents(
        $path, 
        $contents, 
        array $config = [],
        $disk = ''
    ) {
        $path = trim($path, '/');

        $result = $this->filesystem($disk)->write($path, $contents, $config);

        return $result ? $path : false;
    }
    
    public function writeStream(
        $path, 
        $fileStream, 
        array $config = [],
        $disk = ''
    ) {
        $path = trim($path, '/');
        
        $stream = fopen($fileStream, 'r');

        $result = $this->filesystem($disk)->writeStream($path, $stream, $config);

        return $result ? $path : false;
    }
    
    public function objectPath($path = '', $disk = '')
    {
        return $this->filesystem($disk)->getAdapter()->applyPathPrefix($path);
    }
    
    public function objectUrl($path = '', $disk = '')
    {
        if (empty($disk)) {
            $disk = $this->getFilesystemDefaultDisk();
        }
        
        $storage = config('file.storage', []);
        
        $url = Arr::get($storage, $disk.'.url', '');
        
        if (! empty($url)) {
            $url = rtrim($url, '/') . '/';
        }
        if (! empty($path)) {
            $path = ltrim($path, '/');
        }
        
        return $url . $path;
    }
    
    /**
     * 获取保存目录
     */
    public function getSavePath($extension, $path = '', $type = 'unique')
    {
        $path = ($path ? $path.'/' : '');
        if ($type == 'datetime') {
            return $path . $this->generateDatetimeName($extension);
        } else {
            return $path . $this->generateUniqueName($extension);
        }
    }
    
    public function generateDatetimeName($extension)
    {
        return date('YmdHis').mt_rand(10000, 99999).'.'.$extension;
    }
    
    public function generateUniqueName($extension)
    {
        return md5(uniqid().microtime()).'.'.$extension;
    }
    
    public function getFileType($extension)
    {
        $filetype = 'other';
        
        $fileTypes = array_merge($this->fileTypes, config('admin.upload.file_types', []));
        foreach ($fileTypes as $type => $pattern) {
            if (preg_match($pattern, $extension) === 1) {
                $filetype = $type;
                break;
            }
        }
        
        return $filetype;
    }
    
    public function getMimeType($extension, $mimeType = 'other')
    {
        $filetype = $this->getFileType($extension);
        
        if ($filetype == 'video') {
            $mimeType = "video/{$extension}";
        }

        if ($filetype == 'audio') {
            $mimeType = "audio/{$extension}";
        }
        
        return $mimeType;
    }
}
