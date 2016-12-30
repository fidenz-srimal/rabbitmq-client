<?php
namespace Sogarkov\RabbitmqClient\Wrapper;

use Sogarkov\RabbitmqClient\Contracts\RabbitMQRpcServerGatewayInterface;
use Sogarkov\RabbitmqClient\DirectRpcServer;
use Sogarkov\RabbitmqClient\Connector;
use Exception;

class RabbitMQRpcServerGateway implements RabbitMQRpcServerGatewayInterface
{

	private $queueName = null;
	private $exchangeName = null;
	private $bindingKey = null;
	private $directRpcServer = null;
	private $connector = null;

	public function __construct(string $queueName, string $exchangeName, string $bindingKey){
		$this->setConnector();
		if ( $this->connector ) {
			$this->queueName = $queueName;
			$this->exchangeName = $exchangeName;
			$this->bindingKey = $bindingKey;
			$this->directRpcServer = new DirectRpcServer($this->connector);
		} else {
			throw new Exception("Rabbitmq Connector not initated.");
		}
	}


	public function setConnector()
	{
		$this->connector = new Connector(config('rabbitmq_client'));
	}

	public function startRpcServer(callable $callback)
	{
		if(!$this->directRpcServer)
			throw new Exception("DirectRpcServer not initiated.");

		$this->directRpcServer->start($this->queueName, $this->exchangeName, $this->bindingKey, $callback);
	}
}
