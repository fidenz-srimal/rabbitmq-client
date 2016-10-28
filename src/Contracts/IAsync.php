<?php
/**
 * RabbitMq Client
 *
 * @copyright Sergey Ogarkov
 * @author Sergey Ogarkov <sogarkov@gmail.com>
 *
 * @license MIT
 */
namespace Sogarkov\RabbitmqClient\Contracts;

interface IAsync extends IChannel
{
    /**
     * Message to cancel the consume loop
     * @var string
     */
    const MESSAGE_CANCEL_CONSUME = 'quit';
    
    /**
     * Push message to the queue
     * @param string $payload  message body to send.
     * @param string $exchangeName
     * @param string $routingKey a route for delivery. Will be delivered to all queues with the same binging key.
     */
    public function push(string $payload, string $exchangeName = null, string $routingKey = null);
    
    /**
     * Upload a message directly: creates exchange, queue, makes binding and pushes message to a queue
     * @param string $payload
     * @param string $queueName
     * @param string $exchangeName
     * @param string $bindingKey
     */
    public function uploadDirectMessage(string $payload, string $queueName, string $exchangeName, string $bindingKey);
    
    /**
     * Reading queue
     * @param string $queueName
     * @param string|callable $callback function to process message. 
     * Should return IAsync::MESSAGE_CANCEL_CONSUME to interrupt a consume cycle 
     */
    public function consume(string $queueName, $callback);
    
    /**
     * Prepares exchange and queue, binds them and start to listen queue using callback function. 
     * @param string $queueName
     * @param string $exchangeName
     * @param string $bindingKey
     * @param string|callable $callback function to process message. 
     * Should return IAsync::MESSAGE_CANCEL_CONSUME to interrupt a consume cycle 
     */
    public function listenQueue(string $queueName, string $exchangeName, string $bindingKey, $callback);
}