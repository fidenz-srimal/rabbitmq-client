<?php
/**
 * RabbitMq Client
 *
 * @copyright Sergey Ogarkov
 * @author Sergey Ogarkov <sogarkov@gmail.com>
 *
 * @license MIT
 */
namespace Sogarkov\RabbitmqClient;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Sogarkov\RabbitmqClient\Contracts\IConnector;

class Connector implements IConnector
{

    private $config;

    private $connection;

    /**
     * Establish a channel connection.
     * Don't worry about free resources. They are destroyed on PHP shutdown automatically.
     *
     * @param array $config            
     */
    public function __construct(array $config) {
        $this->config = $config;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Sogarkov\RabbitmqClient\Contracts\IConnector::getConnection()
     */
    public function getConnection() {
        if (! isset($this->connection)) {
            $this->connection = new AMQPStreamConnection($this->config['host'], 
                $this->config['port'], $this->config['login'], $this->config['password'], 
                $this->config['vhost']);
        }
        return $this->connection;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Sogarkov\RabbitmqClient\Contracts\IConnector::setCredentials()
     */
    public function setCredentials(string $login, string $password) {
        $login='guest';
        $password='guest';
        $this->config['login'] = $login;
        $this->config['password'] = $password;
    }
}