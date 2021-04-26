<?php

use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\ExceptionHandler\Formatter\FormatterInterface;
use Hyperf\Utils\Context;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Redis\Redis;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\AsyncQueue\JobInterface;

use Lakef\Admin\Support\Upload;

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

if (! function_exists('context')) {
    /**
     * 获取上下文
     * 
     * @param mixed $interface
     * @return mixed
     */
    function context($interface, $object = null)
    {
        if (empty($interface)) {
            return null;
        }
        
        if (empty($object)) {
            return Context::get($interface);
        }
        
        return Context::set($interface, $object);
    }
}

if (! function_exists('context_request')) {
    /**
     * 当前上下文请求
     */
    function context_request($request = null)
    {
        if (! empty($request) 
            && $request instanceof ServerRequestInterface
        ) {
            return context(ServerRequestInterface::class, $request);
        }
        
        return context(ServerRequestInterface::class);
    }
}

if (! function_exists('context_response')) {
    /**
     * 当前上下文响应
     */
    function context_response($response = null)
    {
        if (! empty($response) 
            && $response instanceof PsrResponseInterface
        ) {
            return context(PsrResponseInterface::class, $response);
        }
        
        return context(PsrResponseInterface);
    }
}

if (! function_exists('request')) {
    /**
     * 常规请求
     */
    function request()
    {
        return di(RequestInterface::class);
    }
}

if (! function_exists('response')) {
    /**
     * 常规响应
     */
    function response()
    {
        return di(ResponseInterface::class);
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

if (! function_exists('admin_can')) {
    /**
     * 权限检测
     * 
     * @param null|mixed $permission
     * @return bool
     */
    function admin_can($permission = null)
    {
        // $permission = 'GET:/admin/group/index'
        $info = context_request()->getAttribute('authAdmin')->getData();
        return $info->can($permission);
    }
}

if (! function_exists('admin_url')) {
    /**
     * 后台url
     * 
     * @param null|mixed $url
     * @return mixed
     */
    function admin_url($url = null)
    {
        return config('admin.route.group') . '/' . ltrim($url, '/');
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
        return config('admin.assets.static') . ltrim($assets, '/');
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

if (! function_exists('admin_rand_id')) {
    /**
     * 生成16位ID
     *
     * @return string 返回16位的字符串
     */
    function admin_rand_id() {
        return admin_md5(microtime().mt_rand(10000, 99999));
    }
}

if (! function_exists('admin_json_encode')) {
    /**
     * 返回可阅读json
     */
    function admin_json_encode(array $data) {
        return json_encode($data, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
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