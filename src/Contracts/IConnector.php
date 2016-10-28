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

interface IConnector 
{
	/**
	 * Get connection. Eslablish if not exists.
	 * @return AMQPStreamConnection
	 */
	public function getConnection();
}