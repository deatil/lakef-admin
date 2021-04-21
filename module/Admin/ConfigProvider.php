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
                        __DIR__,
                    ],
                ],
            ],
        ];
    }
}
