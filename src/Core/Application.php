<?php

namespace Pluma\Core;

use Pluma\Container\Container;
use Pluma\Http\Request;
use Pluma\Http\Response;
use Pluma\Http\Router;

/**
 * Main Application Class
 */
class Application
{
    /**
     * @var Container The dependency injection container
     */
    protected Container $container;
    
    /**
     * @var Router The router instance
     */
    protected Router $router;
    
    /**
     * @var Request The current request
     */
    protected Request $request;
    
    /**
     * @var Response The response to be sent
     */
    protected Response $response;
    
    /**
     * @var Bootstrap The bootstrap instance
     */
    protected Bootstrap $bootstrap;
    
    /**
     * Application constructor
     */
    public function __construct()
    {
        $this->container = new Container();
        $this->router = new Router();
        $this->request = new Request();
        $this->response = new Response();
        
        // Register core components in the container
        $this->container->set('router', $this->router);
        $this->container->set('request', $this->request);
        $this->container->set('response', $this->response);
        $this->container->set('app', $this);
        
        // Load configuration
        $this->loadConfiguration();
        
        // Bootstrap the application
        $this->bootstrap = new Bootstrap($this->container);
        $this->bootstrap->bootstrap();
    }
    
    /**
     * Run the application
     */
    public function run(): void
    {
        try {
            // Find the route that matches the current request
            $route = $this->router->resolve($this->request);
            
            if ($route === null) {
                throw new \Exception('Route not found', 404);
            }
            
            // Execute the route handler
            $response = $this->executeRoute($route);
            
            // Send the response
            $this->response->send($response);
            
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * Execute a route handler
     * 
     * @param array $route The route configuration
     * @return mixed The response from the route handler
     */
    protected function executeRoute(array $route)
    {
        $handler = $route['handler'];
        $params = $route['params'] ?? [];
        
        if (is_callable($handler)) {
            // If the handler is a closure, call it with the container
            return call_user_func_array($handler, array_merge([$this->container], $params));
        }
        
        if (is_string($handler) && strpos($handler, '@') !== false) {
            // If the handler is in the format "Controller@method"
            list($controller, $method) = explode('@', $handler);
            
            if (!class_exists($controller)) {
                throw new \Exception("Controller {$controller} not found", 500);
            }
            
            $instance = $this->container->get($controller);
            
            if (!method_exists($instance, $method)) {
                throw new \Exception("Method {$method} not found in controller {$controller}", 500);
            }
            
            return call_user_func_array([$instance, $method], $params);
        }
        
        throw new \Exception("Invalid route handler", 500);
    }
    
    /**
     * Handle exceptions
     * 
     * @param \Exception $e The exception to handle
     */
    protected function handleException(\Exception $e): void
    {
        $statusCode = $e->getCode() ?: 500;
        $message = $e->getMessage() ?: 'Internal Server Error';
        
        $this->response->setStatusCode($statusCode);
        $this->response->send([
            'error' => [
                'code' => $statusCode,
                'message' => $message
            ]
        ]);
    }
    
    /**
     * Load application configuration
     */
    protected function loadConfiguration(): void
    {
        $configPath = PLUMA_ROOT . '/config';
        
        if (is_dir($configPath)) {
            foreach (glob($configPath . '/*.php') as $file) {
                $key = basename($file, '.php');
                $config = require $file;
                $this->container->set("config.{$key}", $config);
            }
        }
    }
    
    /**
     * Get the dependency injection container
     * 
     * @return Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }
}