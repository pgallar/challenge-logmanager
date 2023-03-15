<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    protected $connection;
    protected $channel;

    public function __construct($host, $port, $user, $password, $queue)
    {
        $this->connection = new AMQPStreamConnection($host, $port, $user, $password);
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare($queue, false, true, false, false);
    }

    public function sendMessage($message, $exchange = '', $routing_key = '')
    {
        $msg = new AMQPMessage(json_encode($message), array('delivery_mode' => 2));
        $this->channel->basic_publish($msg, $exchange, $routing_key);
    }

    public function consumeMessage($callback, $queue, $no_ack = true)
    {
        $this->channel->basic_consume($queue, '', false, $no_ack, false, false, $callback);

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
