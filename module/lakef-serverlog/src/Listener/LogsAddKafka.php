<?php

declare(strict_types=1);

namespace Lakef\Serverlog\Listener;

use Psr\Container\ContainerInterface;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Kafka\Producer;

use Lakef\Serverlog\Event\LogsAdd as LogsAddEvent;

/**
 * 日志数据
 */
class LogsAddKafka implements ListenerInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Producer
     */
    private $kafkaProducer;

    public function __construct(
        ContainerInterface $container,
        Producer $kafkaProducer
    ) {
        $this->container = $container;
        $this->kafkaProducer = $kafkaProducer;
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
        $this->kafkaProducer->send('serverlog', json_encode($data), 'data');
    }
}
