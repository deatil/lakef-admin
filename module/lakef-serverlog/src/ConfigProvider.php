<?php

declare(strict_types=1);

namespace Lakef\Serverlog;

use Lakef\Serverlog\Listener\BeforeMainServerStartListener;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'listeners' => [
                BeforeMainServerStartListener::class => 99,
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'watcher' => [
                'watch' => [
                    'dir' => [
                       str_replace(BASE_PATH . '/', '', __DIR__),
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'lakef-serverlog-config',
                    'description' => 'The config for lakef-serverlog.',
                    'source' => __DIR__ . '/../publish/serverlog.php',
                    'destination' => BASE_PATH . '/config/autoload/serverlog.php',
                ],
            ],
        ];
    }
}
