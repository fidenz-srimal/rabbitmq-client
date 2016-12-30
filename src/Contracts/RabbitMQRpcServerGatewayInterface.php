<?php
namespace Sogarkov\RabbitmqClient\Contracts;

/**
 * Interface RabbitMQRpcServerGatewayInterface
 * @package Sogarkov\RabbitmqClient\Contracts
 */
interface RabbitMQRpcServerGatewayInterface
{
	public function startRpcServer(callable $callback);
}
