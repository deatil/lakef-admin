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
                        __DIR__,
                    ],
                ],
            ],
        ];
    }
}
