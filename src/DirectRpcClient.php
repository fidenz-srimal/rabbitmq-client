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

use PhpAmqpLib\Message\AMQPMessage;
use Sogarkov\RabbitmqClient\Contracts\IChannel;
use Sogarkov\RabbitmqClient\Contracts\IDirectRpcClient;

class DirectRpcClient extends Channel implements IDirectRpcClient
{

    private $reply = null;
    private $exchangeName;
    private $bindingKey;

    /**
     *
     * {@inheritdoc}
     *
     * @see \App\Contracts\Rabbitmq\IDirectRpcClient::init()
     */
    public function init(string $queueName, string $exchangeName, string $bindingKey)
    {
        $this->exchangeName = $exchangeName;
        $this->bindingKey = $bindingKey;
        
        // Init command message queue
        $this->declareExchange(IChannel::EXCHANGE_TYPE_DIRECT, $exchangeName);
        $this->declareQueue($queueName);
        $this->bindQueue($queueName, $exchangeName, $bindingKey);
        
        // Listening reply
        $this->channel->basic_consume('amq.rabbitmq.reply-to', '', false, true, false, false, array(
            $this,
            'replyCallback'
        ));
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \App\Contracts\Rabbitmq\IDirectRpcClient::call()
     */
    public function call(string $payload)
    {
        $this->reply = null;
        
        // Create command message
        $msg = new AMQPMessage($payload, array(
            'Content-Type' => 'text/plain',
            'reply_to' => 'amq.rabbitmq.reply-to'
        ));
        
        // Send command message
        $this->channel->basic_publish($msg, $this->exchangeName, $this->bindingKey);
        // Waiting for reply
        while (!$this->reply) {
            $this->channel->wait(0, false, $this->config['rpc_timeout']);
        }
        return $this->reply;
    }

    public function replyCallback(AMQPMessage $message)
    {
        $this->reply = $message->body;
    }
}