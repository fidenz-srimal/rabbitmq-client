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

/**
 * Direct RPC client: Sends message and receives an answer from server 
 * @author sergey
 *
 */
interface IDirectRpcClient extends IChannel
{
    /**
     * Init client queue; Call can be fired multiple times, it is implemented in separate function.
     * @param string $queueName
     * @param string $exchangeName
     * @param string $bindingKey
     */
    public function init(string $queueName, string $exchangeName, string $bindingKey);
    
    /**
     * Send message and wait for reply
     * @param string $payload
     * @exception PhpAmqpLib\Exception\AMQPTimeoutException if server did not reply within RABBITMQ_RPC_TIMEOUT seconds
     * @return string reply from server
     */
    public function call(string $payload);
}