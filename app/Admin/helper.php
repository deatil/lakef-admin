<?php

use Psr\SimpleCache\CacheInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\ExceptionHandler\Formatter\FormatterInterface;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Redis\Redis;
use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\AsyncQueue\JobInterface;
use App\Admin\Support\Upload;

if (! function_exists('admin_url')) {
    /**
     * 后台url
     * 
     * @param null|mixed $url
     * @return mixed
     */
    function admin_url($url = null)
    {
        return '/admin/' . ltrim($url, '/');
    }
}

if (! function_exists('admin_assets')) {
    /**
     * 后台资源
     * 
     * @param null|mixed $assets
     * @return mixed
     */
    function admin_assets($assets = null)
    {
        return '/static/' . ltrim($assets, '/');
    }
}

if (! function_exists('di')) {
    /**
     * 获取Container
     * 
     * @param null|mixed $id
     * @return mixed|\Psr\Container\ContainerInterface
     */
    function di($id = null)
    {
        $container = ApplicationContext::getContainer();
        if ($id) {
            return $container->get($id);
        }
        return $container;
    }
}

if (! function_exists('stdLog')) {
    /**
     * 控制台日志
     */
    function stdLog()
    {
        return di()->get(StdoutLoggerInterface::class);
    }
}

if (! function_exists('logger')) {
    /**
     * 文件日志
     */
    function logger($name = 'hyperf', $group = 'default')
    {
        return di()->get(LoggerFactory::class)->get($name, $group);
    }
}

if (! function_exists('redis')) {
    /**
     * redis 客户端实例
     */
    function redis()
    {
        return di()->get(Redis::class);
    }
}

if (! function_exists('cache')) {
    /**
     * 缓存实例 简单的缓存
     */
    function cache()
    {
        return di()->get(CacheInterface::class);
    }
}

if (! function_exists('format_throwable')) {
    /**
     * Format a throwable to string.
     * @param Throwable $throwable
     * @return string
     */
    function format_throwable(Throwable $throwable): string
    {
        return di()->get(FormatterInterface::class)->format($throwable);
    }
}

if (! function_exists('queue_push')) {
    /**
     * Push a job to async queue.
     */
    function queue_push(JobInterface $job, int $delay = 0, string $key = 'default'): bool
    {
        $driver = di()->get(DriverFactory::class)->get($key);
        return $driver->push($job, $delay);
    }
}

if (! function_exists('admin_md5')) {
    /**
     * 返回16位md5值
     *
     * @param string $str 字符串
     * @return string $str 返回16位的字符串
     */
    function admin_md5($str) {
        return substr(md5($str), 8, 16);
    }
}

if (! function_exists('admin_attachment_url')) {
    /**
     * 获取附件路径
     *
     * @param int $id 附件id
     * @param bool $domain 是否添加域名
     * @return string
     */
    function admin_attachment_url($id, $domain = false)
    {
        return di(Upload::class)->getAttachmentUrl($id, $domain);
    }
}

if (! function_exists('admin_attachment_url_list')) {
    /**
     * 获取多附件地址
     *
     * @param string $ids 附件id列表
     * @param bool $domain 是否添加域名
     * @return 返回附件列表
     */
    function admin_attachment_url_list($ids, $domain = false)
    {
        if ($ids == '') {
            return false;
        }
        
        $id_list = explode(',', $ids);
        foreach ($id_list as $id) {
            $list[] = admin_attachment_url($id, $domain);
        }
        return $list;
    }
}

if (! function_exists('admin_form_image')) {
    /**
     * 单图片上传
     * @param string $name 表单名称
     * @param int $id 表单id
     * @param string $value 表单默认值
     */
    function admin_form_image(
        $name, 
        $id = '', 
        $value = '', 
        $uploadUrl = ''
    ) {
        if (!$id) {
            $id = $name;
        }
        
        if (empty($uploadUrl)) {
            $uploadUrl = admin_url('upload/file');
        }
        
        $string = "
        <script type=\"text/javascript\">
        var images_url = {
            'image_upload_url': '".$uploadUrl."',
            'file_upload_url': '".$uploadUrl."',
            'webuploader_swf': 'lib/webuploader/Uploader.swf',
        };
        </script>
        ";
        
        $string .= "<div id='file_list_{$name}' class='uploader-list'>";
        if (! empty($value)) {
            $path = ($attachmentUrl = admin_attachment_url($value)) ? $attachmentUrl : admin_assets("/admin/images/none.png");
            $string .= "<div class='file-item thumbnail'><img data-original='{$path}' src='{$path}' width='100' style='max-height: 100px;'><i class='fa fa-trash-o remove-picture' data-id='{$value}' title='移除'></i></div>";
        }
        
        $string .= "</div><input type='hidden' name='{$name}' id='{$id}' value='{$value}'><div class='layui-clear'></div><div id='picker_{$name}'>上传单张图片</div>";
        return $string;
    }
}

if (! function_exists('admin_form_images')) {
    /**
     * 多图片上传
     * @param string $name 表单名称
     * @param int $id 表单id
     * @param string $value 表单默认值
     */
    function admin_form_images(
        $name, 
        $id = '', 
        $value = '', 
        $uploadUrl = ''
    ) {
        if (!$id) {
            $id = $name;
        }
        
        if (empty($uploadUrl)) {
            $uploadUrl = admin_url('upload/file');
        }
        
        $string = "
        <script type=\"text/javascript\">
        var images_url = {
            'image_upload_url': '".$uploadUrl."',
            'file_upload_url': '".$uploadUrl."',
            'webuploader_swf': 'lib/webuploader/Uploader.swf',
        };
        </script>
        ";
        
        $string .= "<div id='file_list_{$name}' class='uploader-list'>";
        if (! empty($value)) {
            $path = ($attachmentUrl = admin_attachment_url($value)) ? $attachmentUrl : admin_assets("/admin/images/none.png");
            $string .= "<div class='file-item thumbnail'><img data-original='{$path}' src='{$path}' width='100' style='max-height: 100px;'><i class='fa fa-trash-o remove-picture' data-id='{$value}' title='移除'></i></div>";
        }
        
        $string .= "</div><input type='hidden' name='{$name}' id='{$id}' value='{$value}' data-multiple='true'><div class='layui-clear'></div><div id='picker_{$name}'>上传单张图片</div>";
        return $string;
    }
}