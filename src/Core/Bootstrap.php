<?php

namespace Pluma\Core;

use Pluma\Container\Container;
use Pluma\Database\Database;
use Pluma\Http\Request;
use Pluma\Http\Response;
use Pluma\Http\Router;
use Pluma\View\View;

/**
 * Bootstrap the application
 */
class Bootstrap
{
    /**
     * @var Container The dependency injection container
     */
    protected Container $container;
    
    /**
     * Bootstrap constructor
     * 
     * @param Container $container The dependency injection container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    
    /**
     * Bootstrap the application
     * 
     * @return void
     */
    public function bootstrap(): void
    {
        $this->registerServices();
        $this->loadRoutes();
    }
    
    /**
     * Register services in the container
     * 
     * @return void
     */
    protected function registerServices(): void
    {
        // Register the view service
        $this->container->singleton(View::class, function ($container) {
            $viewPath = PLUMA_ROOT . '/resources/views';
            return new View($viewPath);
        });
        
        // Register the database service if configuration exists
        if ($this->container->has('config.database')) {
            $this->container->singleton(Database::class, function ($container) {
                $config = $container->get('config.database');
                $connection = $config['connections'][$config['default']];
                return new Database($connection);
            });
        }
    }
    
    /**
     * Load routes
     * 
     * @return void
     */
    protected function loadRoutes(): void
    {
        $router = $this->container->get('router');
        $routesPath = PLUMA_ROOT . '/config/routes.php';
        
        if (file_exists($routesPath)) {
            $routes = require $routesPath;
            
            if (is_callable($routes)) {
                $routes($router);
            }
        }
    }
}