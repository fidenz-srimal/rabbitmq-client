<?php
namespace Sogarkov\RabbitmqClient\Wrapper;

use Sogarkov\RabbitmqClient\Contracts\RabbitMQAsyncClientGatewayInterface;
use Sogarkov\RabbitmqClient\Async;
use Sogarkov\RabbitmqClient\Wrapper\RabbitMQAsyncBase;
use Exception;

class RabbitMQAsyncClientGateway extends RabbitMQAsyncBase implements RabbitMQAsyncClientGatewayInterface
{

	public function asyncPushToQueue(string $payload)
	{
		if(!$this->async)
			throw new Exception("Rabbitmq Async not initiated.");

		$this->async->uploadDirectMessage($payload, $this->queueName, $this->exchangeName, $this->bindingKey);
	}

}
