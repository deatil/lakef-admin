<?php

declare(strict_types=1);

namespace Lakef\Serverlog\Listener;

use Hyperf\Event\Contract\ListenerInterface;
use Psr\Container\ContainerInterface;

use Lakef\Serverlog\Event\LogsAdd as LogsAddEvent;
use Lakef\Serverlog\Model\Logs as LogsModel;

/**
 * 日志数据
 */
class LogsAddModel implements ListenerInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return string[] returns the events that you want to listen
     */
    public function listen(): array
    {
        return [
            LogsAddEvent::class,
        ];
    }

    /**
     * Handle the Event when the event is triggered, all listeners will
     * complete before the event is returned to the EventDispatcher.
     */
    public function process(object $event)
    {
        $data = $event->data;
        
        // 记录日志
        LogsModel::record($data);
    }
}
