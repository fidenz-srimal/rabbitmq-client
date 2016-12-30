<?php
namespace Sogarkov\RabbitmqClient\Contracts;

/**
 * Interface RabbitMQRpcClientGatewayInterface
 * @package Sogarkov\RabbitmqClient\Contracts
 */
interface RabbitMQRpcClientGatewayInterface
{
	public function sendMessage(string $payload);
}
