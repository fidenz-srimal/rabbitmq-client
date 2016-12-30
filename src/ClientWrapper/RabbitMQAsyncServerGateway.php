<?php
namespace Sogarkov\RabbitmqClient\Wrapper;

use Sogarkov\RabbitmqClient\Contracts\RabbitMQAsyncServerGatewayInterface;
use Sogarkov\RabbitmqClient\Async;
use Sogarkov\RabbitmqClient\Wrapper\RabbitMQAsyncBase;
use Exception;

class RabbitMQAsyncServerGateway extends RabbitMQAsyncBase implements RabbitMQAsyncServerGatewayInterface
{

	public function asyncListenToQueue($callback)
	{
		if(!$this->async)
			throw new Exception("Rabbitmq Async not initiated.");

		$this->async->listenQueue($this->queueName, $this->exchangeName, $this->bindingKey, $callback);
	}

}
