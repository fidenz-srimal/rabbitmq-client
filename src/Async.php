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
use Sogarkov\RabbitmqClient\Contracts\IAsync;
use PhpAmqpLib\Message\AMQPMessage;

class Async extends Channel implements IAsync
{
    protected $callback = null;
    protected $consumer_tag;

    /**
     * 
     * {@inheritDoc}
     * @see \Sogarkov\RabbitmqClient\Contracts\IAsync::push()
     */
    public function push(string $payload, string $exchangeName = null, string $routingKey = null)
    {
        $headers = array(
            'Content-Type' => $this->config['content_type'],
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        );
        $message = new AMQPMessage($payload, $headers);
        $this->channel->basic_publish($message, $exchangeName, $routingKey);
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \Sogarkov\RabbitmqClient\Contracts\IAsync::consume()
     */
    public function consume(string $queueName, $callback)
    {
        $this->callback = $callback;
        $this->consumer_tag = $this->channel->basic_consume($queueName, '', false, false, false, false, array(
            $this,
            'consumeCallback'
        ));
        // Loop as long as the channel has callbacks registered or cycle is interrupted
        $this->wait();
    }
    
    /**
     * Wait cycle<br>
     * Loop as long as the channel has callbacks registered or cycle is interrupted
     * @exception AMQPTimeoutException on wait_timeout
     */
    protected function wait() {
        while (count($this->channel->callbacks)) {
            $this->channel->wait(
                null,
                $this->config['wait_non_blocking'],
                $this->config['wait_timeout']);
        }
    }
    
    public function consumeCallback(AMQPMessage $message)
    {
        $result=call_user_func($this->callback, $message->body);
        // Quit the consume loop
        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
        if ($result === IAsync::MESSAGE_CANCEL_CONSUME) {
            $message->delivery_info['channel']->basic_cancel($message->delivery_info['consumer_tag']);
        }
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \Sogarkov\RabbitmqClient\Contracts\IAsync::uploadDirectMessage()
     */
    public function uploadDirectMessage(string $payload, string $queueName, string $exchangeName, string $bindingKey)
    {
        $this->declareExchange(IChannel::EXCHANGE_TYPE_DIRECT, $exchangeName);
        $this->declareQueue($queueName);
        $this->bindQueue($queueName, $exchangeName, $bindingKey);
        $this->push($payload, $exchangeName, $bindingKey);
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \Sogarkov\RabbitmqClient\Contracts\IAsync::listenQueue()
     */
    public function listenQueue(string $queueName, string $exchangeName, string $bindingKey, $callback)
    {
        $this->declareExchange(IChannel::EXCHANGE_TYPE_DIRECT, $exchangeName);
        $this->declareQueue($queueName);
        $this->bindQueue($queueName, $exchangeName, $bindingKey);
        $this->consume($queueName, $callback);
    }
    
}
