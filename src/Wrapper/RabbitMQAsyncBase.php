<?php
namespace Sogarkov\RabbitmqClient\Wrapper;

use Sogarkov\RabbitmqClient\Async;
use Sogarkov\RabbitmqClient\Connector;
use Exception;

abstract class RabbitMQAsyncBase
{
	
	protected $connector = null;
	protected $async = null;
	protected $queueName = null;
	protected $exchangeName = null;
	protected $bindingKey = null;

	public function __construct(string $queueName, string $exchangeName, string $bindingKey)
	{
		$this->setConnector();
		$this->initAsync($queueName,$exchangeName,$bindingKey);
	}

	public function setConnector()
	{
		$this->connector = new Connector(config('rabbitmq_client'));
	}

	public function initAsync(string $queueName, string $exchangeName, string $bindingKey)
	{
		if ($this->connector) {
			$this->async = new Async($this->connector);
			$this->queueName = $queueName;
			$this->exchangeName = $exchangeName;
			$this->bindingKey = $bindingKey;
		} else {
			throw new Exception("Rabbitmq Connector not initated.");
		}
	}
}