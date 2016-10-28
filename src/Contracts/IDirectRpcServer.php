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

interface IDirectRpcServer extends IChannel
{
    /**
     * Direct RPC server: Receives a message and replies result 
     * @param string $queueName
     * @param string $exchangeName
     * @param string $bindingKey
     * @param callable $callback user callback function to process received message
     */
    public function start(string $queueName, string $exchangeName, string $bindingKey, callable $callback);
}