<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller;

use longlang\phpkafka\Producer\Producer;
use longlang\phpkafka\Producer\ProducerConfig;
use longlang\phpkafka\Consumer\ConsumeMessage;
use longlang\phpkafka\Consumer\Consumer;
use longlang\phpkafka\Consumer\ConsumerConfig;

class IndexController extends AbstractController
{
    public function index2()
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        return [
            'method' => $method,
            'message' => "Hello123 {$user}.",
        ];
    }
    
    public function index()
    {
        $config = new ProducerConfig();
        $config->setBootstrapServer('kafka:9092');
        $config->setUpdateBrokers(true);
        $config->setAcks(-1);
        $producer = new Producer($config);
        $topic = 'test';
        $value = (string) microtime(true);
        $key = uniqid('', true);
        $producer->send('test', $value, $key);

        return [
            'data' => 'Producer',
        ];
    }
    
    public function consumer()
    {
        $config = new ConsumerConfig();
        $config->setBroker('kafka:9092');
        $config->setTopic('test'); // 主题名称
        $config->setGroupId('testGroup'); // 分组ID
        $config->setClientId('test'); // 客户端ID，不同的消费者进程请使用不同的设置
        $config->setGroupInstanceId('test'); // 分组实例ID，不同的消费者进程请使用不同的设置
        $config->setInterval(0.1);
        $consumer = new Consumer($config, function(ConsumeMessage $message) {
            var_dump($message->getKey() . ':' . $message->getValue());
            // $consumer->ack($message); // autoCommit设为false时，手动提交
        });
        $consumer->start();

        return [
            'data' => 'Consumer',
        ];
    }
}
