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

interface IChannel
{
    const EXCHANGE_TYPE_DIRECT = 'direct';

    const EXCHANGE_TYPE_TOPIC = 'topic';

    const EXCHANGE_TYPE_HEADERS = 'headers';

    const EXCHANGE_TYPE_FANOUT = 'fanout';

    /**
     * Declare a queue
     * 
     * @param string $name
     *            queue name
     */
    public function declareQueue(string $name);

    /**
     * Declare an exchange
     * 
     * @param string $type
     *            exchange type
     * @param string $name
     *            exchange name
     */
    public function declareExchange(string $type = null, string $name = null);

    /**
     * Bind queue to exchange
     * 
     * @param string $queueName            
     * @param string $exchangeName            
     * @param string $bindingKey
     *            a key to create named binding. Uses in direct routing.
     */
    public function bindQueue(string $queueName, string $exchangeName = null, string $bindingKey = null);
}
