<?php

use Psr\SimpleCache\CacheInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\ExceptionHandler\Formatter\FormatterInterface;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Redis\Redis;
use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\AsyncQueue\JobInterface;

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

if (! function_exists('config')) {
    /**
     * 配置
     */
    function config()
    {
        return di()->get(ConfigInterface::class);
    }
}

if (! function_exists('request')) {
    /**
     * 请求
     */
    function request()
    {
        return di()->get(RequestInterface::class);
    }
}

if (! function_exists('response')) {
    /**
     * 响应
     */
    function response()
    {
        return di()->get(ResponseInterface::class);
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

if (!function_exists('logger')) {
    /**
     * 文件日志
     */
    function logger($name = 'hyperf', $group = 'default')
    {
        return di()->get(LoggerFactory::class)->get($name, $group);
    }
}

if (!function_exists('redis')) {
    /**
     * redis 客户端实例
     */
    function redis()
    {
        return di()->get(Redis::class);
    }
}

if (!function_exists('cache')) {
    /**
     * 缓存实例 简单的缓存
     */
    function cache()
    {
        return di()->get(CacheInterface::class);
    }
}

if (!function_exists('format_throwable')) {
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

if (!function_exists('queue_push')) {
    /**
     * Push a job to async queue.
     */
    function queue_push(JobInterface $job, int $delay = 0, string $key = 'default'): bool
    {
        $driver = di()->get(DriverFactory::class)->get($key);
        return $driver->push($job, $delay);
    }
}
