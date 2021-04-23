<?php

declare(strict_types=1);

namespace Lakef\Admin;

use Lakef\Admin\Listener\MorphMapRelationListener;
use Lakef\Admin\Listener\BeforeMainServerStartListener;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'listeners' => [
                MorphMapRelationListener::class => 99,
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
            'view' => [
                'namespaces' => [
                    'admin' => __DIR__ . '/../resources/view',
                ],
            ],
            'publish' => [
                [
                    'id' => 'lekef-admin-config',
                    'description' => 'The config for lekef-admin.',
                    'source' => __DIR__ . '/../publish/admin.php',
                    'destination' => BASE_PATH . '/config/autoload/admin.php',
                ],
            ],
        ];
    }
}
