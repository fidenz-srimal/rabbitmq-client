<?php
/**
 * RabbitMq Client
 *
 * @copyright Sergey Ogarkov
 * @author Sergey Ogarkov <sogarkov@gmail.com>
 *
 * @license MIT
 */
namespace Sogarkov\RabbitmqClient;

use Sogarkov\RabbitmqClient\Contracts\IChannel;

class Channel implements IChannel
{

    protected $channel;

    protected $config;

    /**
     * Constructor
     * @param App\WebServices\Rabbitmq\Connector $connector
     * @param array $options options to modify default Config
     */
    public function __construct(\App\WebServices\Rabbitmq\Connector $connector, array $options=[])
    {
        $connection = $connector->getConnection();
        $this->channel = $connection->channel($connection->get_free_channel_id());
        $this->config = array_merge(config('rabbitmq'), $options);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \App\Contracts\Rabbitmq\IChannel::declareQueue()
     */
    public function declareQueue(string $name)
    {
        if ($this->config['queue_declare_bind']) {
            $conf = $this->config['queue_params'];
            $this->channel->queue_declare($name, $conf['passive'], $conf['durable'], $conf['exclusive'], $conf['auto_delete'], false, [
                'x-message-ttl' => ['I', intval($this->config['message_expiration'])*1000]
            ]);
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \App\Contracts\Rabbitmq\IChannel::declareExchange()
     */
    public function declareExchange(string $type = null, string $name = null)
    {
        if ($this->config['exchange_declare']) {
            $conf = $this->config['exchange_params'];
            $this->channel->exchange_declare($name ?? $conf['name'], $type ?? $conf['type'], $conf['passive'], $conf['durable'], $conf['auto_delete']);
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \App\Contracts\Rabbitmq\IChannel::bindQueue()
     */
    public function bindQueue(string $queueName, string $exchangeName = null, string $bindingKey = null)
    {
        if ($this->config['queue_declare_bind']) {
            $this->channel->queue_bind($queueName, $exchangeName, $bindingKey);
        }
    }
}