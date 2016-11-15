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
     * Get connection.
     * Eslablish if not exists.
     * 
     * @return AMQPStreamConnection
     */
    public function getConnection();

    /**
     * Sets login and password for connection;
     * Useful for the case when credetials obtains not from config but dynamically.
     * 
     * @param string $login            
     * @param string $password            
     */
    public function setCredentials(string $login, string $password);
}