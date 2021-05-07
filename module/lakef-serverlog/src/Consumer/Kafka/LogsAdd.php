<?php

declare(strict_types=1);

namespace Lakef\Serverlog\Consumer\Kafka;

use Hyperf\Kafka\AbstractConsumer;
use Hyperf\Kafka\Annotation\Consumer;
use longlang\phpkafka\Consumer\ConsumeMessage;

use Lakef\Serverlog\Model\Logs as LogsModel;

/**
 * @Consumer(topic="serverlog", nums=1, groupId="serverlogGroup", autoCommit=true)
 */
class LogsAdd extends AbstractConsumer
{
    public function consume(ConsumeMessage $message): string
    {
        // var_dump($message->getTopic() . ':' . $message->getKey() . ':' . $message->getValue());
        $data = $message->getValue();
        
        // 记录日志
        LogsModel::record(json_decode($data, true));
    }
}