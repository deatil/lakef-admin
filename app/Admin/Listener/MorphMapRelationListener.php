<?php

declare(strict_types=1);

namespace App\Admin\Listener;

use Hyperf\Database\Model\Relations\Relation;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BootApplication;

// 引用文件夹
use App\Admin\Model;

/**
 * @Listener
 */
class MorphMapRelationListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            BootApplication::class,
        ];
    }

    public function process(object $event)
    {
        Relation::morphMap([
            'admin' => Model\Admin::class,
        ]);
    }
}
