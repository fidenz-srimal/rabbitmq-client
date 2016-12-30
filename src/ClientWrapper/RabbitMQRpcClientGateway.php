<?php
namespace Sogarkov\RabbitmqClient\Wrapper;

use Sogarkov\RabbitmqClient\Contracts\RabbitMQRpcClientGatewayInterface;
use Sogarkov\RabbitmqClient\DirectRpcClient;
use Exception;

class RabbitMQRpcClientGateway implements RabbitMQRpcClientGatewayInterface
{
	private $directRpcClient = null;
	private $connector = null;

	public function __construct(string $queueName, string $exchangeName, string $bindingKey)
	{
		$this->setConnector();
		$this->initDirectRpcClient($queueName, $exchangeName, $bindingKey);
	}

	public function setConnector()
	{
        $this->config_path = __DIR__ .'/../../config/rabbitmq_client.php';
        $this->mergeConfigFrom($this->config_path, 'rabbitmq_client');

		$this->connector = new Connector(config('rabbitmq_client'));
	}

	public function initDirectRpcClient(string $queueName, string $exchangeName, string $bindingKey)
	{
		if ($this->connector) {
			$this->directRpcClient = new DirectRpcClient($this->connector);
			$this->directRpcClient->init($queueName,$exchangeName,$bindingKey);
		} else {
			throw new Exception("Rabbitmq Connector not initated.");
		}
	}

	public function sendMessage(string $payload)
	{
		if(!$this->directRpcClient)
			throw new Exception("DirectRpcClient not initiated.");

		return $this->directRpcClient->call($payload);
	}
}
