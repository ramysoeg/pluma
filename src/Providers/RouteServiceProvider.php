<?php

namespace Pluma\Providers;

use Pluma\Container\Container;
use Pluma\Http\Route;
use Pluma\Http\Router;

/**
 * Route Service Provider
 */
class RouteServiceProvider
{
    /**
     * The container instance
     */
    protected Container $container;
    
    /**
     * Constructor
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    
    /**
     * Register the service provider
     */
    public function register(): void
    {
        // Get the router instance
        $router = $this->container->get('router');
        
        // Set the router instance in the Route facade
        Route::setRouter($router);
    }
    
    /**
     * Boot the service provider
     */
    public function boot(): void
    {
        $this->loadRoutes();
    }
    
    /**
     * Load the routes
     */
    protected function loadRoutes(): void
    {
        $this->loadRoutesFrom(PLUMA_ROOT . '/routes/web.php');
        $this->loadRoutesFrom(PLUMA_ROOT . '/routes/api.php');
    }
    
    /**
     * Load routes from a file
     */
    protected function loadRoutesFrom(string $path): void
    {
        if (file_exists($path)) {
            require $path;
        }
    }
}