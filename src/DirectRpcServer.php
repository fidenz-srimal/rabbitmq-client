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
use Sogarkov\RabbitmqClient\Contracts\IDirectRpcServer;
use PhpAmqpLib\Message\AMQPMessage;

class DirectRpcServer extends Channel implements IDirectRpcServer
{
    private $callback;
    
    /**
     *
     * {@inheritdoc}
     *
     * @see \App\Contracts\Rabbitmq\IDirectRpcServer::start()
     */
    
    public function start(string $queueName, string $exchangeName, string $bindingKey, callable $callback) {
        $this->callback = $callback;
        
        // Init queue
        $this->declareExchange(IChannel::EXCHANGE_TYPE_DIRECT, $exchangeName);
        $this->declareQueue($queueName);
        $this->bindQueue($queueName, $exchangeName, $bindingKey);

        // Listening queue
        $this->channel->basic_qos(null, 1, null);
        $this->channel->basic_consume($queueName, '', false, false, false, false, [$this, 'baseCallback']);
        
        while(count($this->channel->callbacks)) {
            $this->channel->wait();
        }
        
    }
    
    public function baseCallback(AMQPMessage $message) {
        // Process message by calling user callback
        $reply = call_user_func($this->callback, $message->body);
        // Create reply message
        $msg_reply = new AMQPMessage($reply);
        // Send reply
        $message->delivery_info['channel']->basic_publish($msg_reply, '', $message->get('reply_to'));
        // Confirm the message is processed
        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
    }
    
}
