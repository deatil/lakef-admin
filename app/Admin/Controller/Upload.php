<?php

declare (strict_types = 1);

namespace App\Admin\Controller;

use App\Admin\Support\Upload as Uploader;
use App\Admin\Model\Attachment as AttachmentModel;

/**
 * 上传
 *
 * create: 2021-4-18
 * author: deatil
 */
class Upload extends Base
{
    /**
     * 上传文件
     */
    public function postFile()
    {
        if (! $this->request->hasFile('file')) {
            return $this->errorJson('上传文件不能为空');
        }
        
        $requestFile = $this->request->file('file');
        
        if (! $requestFile->isValid()) {
            return $this->errorJson('上传文件已失效');
        }
        
        // 临时目录
        $pathname = $requestFile->getPathname();
        
        // 原始名称
        $name = $requestFile->getClientFilename();
        
        // mimeType
        $mimeType = $requestFile->getMimeType();
        
        // 扩展名
        $extension = $requestFile->getExtension();
        
        // 大小
        $size = $requestFile->getSize();
        
        $md5 = hash_file('md5', $pathname);
        
        $sha1 = hash_file('sha1', $pathname);
        
        $uploadDisk = config('admin.upload.disk', '');
        
        $driver = $uploadDisk ?: 'local';
        
        $uploadService = di(Uploader::class);
        
        $filetype = $uploadService->getFileType($extension);
        
        $fileInfo = AttachmentModel::where([
            'md5' => $md5
        ])->first();
        if (!empty($fileInfo)) {
            @unlink($pathname);
            
            AttachmentModel::where([
                    'md5' => $md5
                ])
                ->update([
                    'update_time' => time(), 
                ]);
            
            $res = [
                'id' => $fileInfo['id'],
                'type' => $filetype,
            ];
            if (in_array($filetype, ['image', 'video', 'audio'])) {
                $url = $uploadService->objectUrl($fileInfo['url']);
                
                $res['path'] = $url;
            }
            
            return $this->successJson('上传文件成功', $res);
        }
        
        if ($filetype == 'image') {
            $uploadDir = config('admin.upload.directory.image');
        } elseif ($filetype == 'video' || $filetype == 'audio') {
            $uploadDir = config('admin.upload.directory.media');
        } else {
            $uploadDir = config('admin.upload.directory.file');
        }
        
        $path = $uploadService->writeStream(
            $uploadService->getSavePath($extension, $uploadDir), 
            $pathname
        );
        
        $data = [
            'id' => admin_md5(microtime().mt_rand(10000, 99999)),
            'name' => $name,
            'path' => $path,
            'mime' => $mimeType,
            'ext' => $extension,
            'size' => $size,
            'md5' => $md5,
            'sha1' => $sha1,
            'driver' => $driver,
            'create_time' => time(),
            'update_time' => time(),
            'add_time' => time(),
            'add_ip' => $this->request->server('remote_addr'),
        ];
        $attachment = AttachmentModel::create($data);
        if ($attachment === false) {
            $uploadService->deleteFile($path);
            return $this->errorJson('上传文件失败');
        }
        
        $res = [
            'id' => $attachment->first()->id,
            'type' => $filetype,
        ];
        if (in_array($filetype, ['image', 'video', 'audio'])) {
            $url = $uploadService->objectUrl($path);
            
            $res['path'] = $url;
        }
        
        return $this->successJson('上传文件成功', $res);
    }
    
}
