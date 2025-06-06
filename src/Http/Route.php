<?php

namespace Pluma\Http;

/**
 * Route Facade
 */
class Route
{
    /**
     * The router instance
     */
    protected static ?Router $router = null;
    
    /**
     * Set the router instance
     */
    public static function setRouter(Router $router): void
    {
        static::$router = $router;
    }
    
    /**
     * Get the router instance
     */
    public static function getRouter(): Router
    {
        if (static::$router === null) {
            throw new \RuntimeException('Router not set');
        }
        
        return static::$router;
    }
    
    /**
     * Register a GET route
     */
    public static function get(string $path, mixed $handler): void
    {
        static::getRouter()->get($path, static::normalizeHandler($handler));
    }
    
    /**
     * Register a POST route
     */
    public static function post(string $path, mixed $handler): void
    {
        static::getRouter()->post($path, static::normalizeHandler($handler));
    }
    
    /**
     * Register a PUT route
     */
    public static function put(string $path, mixed $handler): void
    {
        static::getRouter()->put($path, static::normalizeHandler($handler));
    }
    
    /**
     * Register a PATCH route
     */
    public static function patch(string $path, mixed $handler): void
    {
        static::getRouter()->patch($path, static::normalizeHandler($handler));
    }
    
    /**
     * Register a DELETE route
     */
    public static function delete(string $path, mixed $handler): void
    {
        static::getRouter()->delete($path, static::normalizeHandler($handler));
    }
    
    /**
     * Register a route for multiple HTTP methods
     */
    public static function match(array $methods, string $path, mixed $handler): void
    {
        foreach ($methods as $method) {
            static::getRouter()->addRoute(strtoupper($method), $path, static::normalizeHandler($handler));
        }
    }
    
    /**
     * Register a route for any HTTP method
     */
    public static function any(string $path, mixed $handler): void
    {
        static::match(['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], $path, $handler);
    }
    
    /**
     * Register a resource route
     */
    public static function resource(string $name, string $controller): void
    {
        static::get("/{$name}", [$controller, 'index']);
        static::get("/{$name}/create", [$controller, 'create']);
        static::post("/{$name}", [$controller, 'store']);
        static::get("/{$name}/{id}", [$controller, 'show']);
        static::get("/{$name}/{id}/edit", [$controller, 'edit']);
        static::put("/{$name}/{id}", [$controller, 'update']);
        static::delete("/{$name}/{id}", [$controller, 'destroy']);
    }
    
    /**
     * Register an API resource route
     */
    public static function apiResource(string $name, string $controller): void
    {
        static::get("/{$name}", [$controller, 'index']);
        static::post("/{$name}", [$controller, 'store']);
        static::get("/{$name}/{id}", [$controller, 'show']);
        static::put("/{$name}/{id}", [$controller, 'update']);
        static::delete("/{$name}/{id}", [$controller, 'destroy']);
    }
    
    /**
     * Normalize the route handler
     */
    protected static function normalizeHandler(mixed $handler): mixed
    {
        // If the handler is an array with a controller class and method
        if (is_array($handler) && count($handler) === 2 && is_string($handler[0]) && is_string($handler[1])) {
            return $handler[0] . '@' . $handler[1];
        }
        
        // If the handler is a closure, wrap it to match the expected signature
        if ($handler instanceof \Closure) {
            return function ($container, ...$params) use ($handler) {
                $result = $handler(...$params);
                
                // If the result is a string, send it as HTML
                if (is_string($result)) {
                    $response = $container->get('response');
                    $response->setHeader('Content-Type', 'text/html');
                    $response->send($result);
                }
                // If the result is an array, send it as JSON
                elseif (is_array($result)) {
                    $response = $container->get('response');
                    $response->setHeader('Content-Type', 'application/json');
                    $response->send(json_encode($result));
                }
            };
        }
        
        return $handler;
    }
}