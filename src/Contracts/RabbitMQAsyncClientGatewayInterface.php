<?php
namespace Sogarkov\RabbitmqClient\Contracts;

/**
 * Interface RabbitMQAsyncClientGatewayInterface
 * @package Sogarkov\RabbitmqClient\Contracts
 */
interface RabbitMQAsyncClientGatewayInterface
{
	public function asyncPushToQueue(string $payload);
}
