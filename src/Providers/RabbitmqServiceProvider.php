<?php
/**
 * RabbitMq Client
 *
 * @copyright Sergey Ogarkov
 * @author Sergey Ogarkov <sogarkov@gmail.com>
 *
 * @license MIT
 */
namespace Sogarkov\RabbitmqClient\Providers;

use Illuminate\Support\ServiceProvider;
use Sogarkov\RabbitmqClient\Connector;

class RabbitmqServiceProvider extends ServiceProvider
{
    private $config_path;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;
    
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Laravel
        if(function_exists('config_path')) {
            $path = config_path('rabbitmq_client.php');
        }
        // Lumen
        else {
            $path = $this->app->getConfigurationPath('rabbitmq_client');
        }
        $this->publishes([
            $this->config_path => $path,
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->config_path = __DIR__ .'/../../config/rabbitmq_client.php';
        $this->mergeConfigFrom($this->config_path, 'rabbitmq_client');
        
        $this->app->singleton('Sogarkov\RabbitmqClient\Contracts\IConnector', function () {
            return new Connector(config('rabbitmq_client'));
        }); 
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Sogarkov\RabbitmqClient\Contracts\IConnector'];
    }

}