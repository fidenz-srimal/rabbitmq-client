<?php
namespace Sogarkov\RabbitmqClient\Contracts;

/**
 * Interface RabbitMQAsyncServerGatewayInterface
 * @package Sogarkov\RabbitmqClient\Contracts
 */
interface RabbitMQAsyncServerGatewayInterface
{
	public function asyncListenToQueue($callback);
}
