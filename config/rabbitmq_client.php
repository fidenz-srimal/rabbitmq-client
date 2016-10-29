<?php

/**
 * default configuration for laravel-queue-rabbitmq merged with project config to base key 'queue'.
 *
 * @see \MapleSyrupGroup\AMQPEvents\Providers\AMQPEventServiceProvider
 */
return [

    'driver' => 'rabbitmq',

    'host' => '127.0.0.1',
    'port' => 5672,

    'vhost'    => '/',
    'login'    => 'guest',
    'password' => 'guest',

    // create the exchange if not exists
    'exchange_declare' => true,
    // create the queue if not exists and bind to the exchange
    'queue_declare_bind' => true,

    'queue_params' => [
        'passive'     => false,
        'durable'     => true,
        'exclusive'   => false,
        'auto_delete' => false,
    ],
    'exchange_params' => [
        'name' => null,
        // more info at http://www.rabbitmq.com/tutorials/amqp-concepts.html
        'type' => 'direct',
        'passive' => false,
        // the exchange will survive server restarts
        'durable' => true,
        'auto_delete' => false,
    ],
    
    // The Message time to live in queue, s (0 - unlimited)
    'message_expiration' => 0,
    // the RPC server response timeout, s (0 - unlimited)
    'rpc_timeout' => 0,
    /**
     *  Consume wait timeout, s. If no responce received then select is interrupted and
     *  wait makes a loop to refresh its state.
     */
    'wait_timeout' => 0,
    'wait_non_blocking' => false, 
    

];
