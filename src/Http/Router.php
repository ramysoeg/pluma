<?php

namespace Pluma\Http;

/**
 * HTTP Router Class
 */
class Router
{
    /**
     * The registered routes
     */
    protected array $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'PATCH' => [],
        'DELETE' => [],
    ];
    
    /**
     * The route group prefix
     */
    protected string $prefix = '';
    
    /**
     * Register a GET route
     */
    public function get(string $path, mixed $handler): self
    {
        return $this->addRoute('GET', $path, $handler);
    }
    
    /**
     * Register a POST route
     */
    public function post(string $path, mixed $handler): self
    {
        return $this->addRoute('POST', $path, $handler);
    }
    
    /**
     * Register a PUT route
     */
    public function put(string $path, mixed $handler): self
    {
        return $this->addRoute('PUT', $path, $handler);
    }
    
    /**
     * Register a PATCH route
     */
    public function patch(string $path, mixed $handler): self
    {
        return $this->addRoute('PATCH', $path, $handler);
    }
    
    /**
     * Register a DELETE route
     */
    public function delete(string $path, mixed $handler): self
    {
        return $this->addRoute('DELETE', $path, $handler);
    }
    
    /**
     * Register a route for multiple HTTP methods
     */
    public function match(array $methods, string $path, mixed $handler): self
    {
        foreach ($methods as $method) {
            $this->addRoute(strtoupper($method), $path, $handler);
        }
        
        return $this;
    }
    
    /**
     * Register a route for all HTTP methods
     */
    public function any(string $path, mixed $handler): self
    {
        return $this->match(['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], $path, $handler);
    }
    
    /**
     * Create a route group with a prefix
     */
    public function group(string $prefix, callable $callback): self
    {
        // Save the current prefix
        $previousPrefix = $this->prefix;
        
        // Add the new prefix
        $this->prefix = $previousPrefix . $prefix;
        
        // Execute the callback
        $callback($this);
        
        // Restore the previous prefix
        $this->prefix = $previousPrefix;
        
        return $this;
    }
    
    /**
     * Add a route to the router
     */
    public function addRoute(string $method, string $path, mixed $handler): self
    {
        // Prepend the prefix to the path
        $path = $this->prefix . '/' . ltrim($path, '/');
        
        // Normalize the path (remove duplicate slashes)
        $path = '/' . trim($path, '/');
        
        // Convert array handler to string format
        if (is_array($handler) && count($handler) === 2) {
            if (is_object($handler[0]) && $handler[0] instanceof \Closure) {
                // It's a closure, keep it as is
            } elseif (is_string($handler[0]) && is_string($handler[1])) {
                // It's a controller class and method
                $handler = $handler[0] . '@' . $handler[1];
            }
        }
        
        $this->routes[$method][$path] = [
            'path' => $path,
            'handler' => $handler,
        ];
        
        return $this;
    }
    
    /**
     * Resolve a route from a request
     */
    public function resolve(Request $request): ?array
    {
        $method = $request->getRequestMethod();
        $path = $request->getRequestPath();
        
        // Check for exact match
        if (isset($this->routes[$method][$path])) {
            return $this->routes[$method][$path];
        }
        
        // Check for pattern match
        foreach ($this->routes[$method] as $routePath => $route) {
            $pattern = $this->convertRouteToRegex($routePath);
            
            if (preg_match($pattern, $path, $matches)) {
                // Remove the full match
                array_shift($matches);
                
                // Add the parameters to the route
                $route['params'] = $matches;
                
                return $route;
            }
        }
        
        return null;
    }
    
    /**
     * Convert a route path to a regular expression
     */
    protected function convertRouteToRegex(string $route): string
    {
        // Replace route parameters with regex patterns
        $route = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route);
        
        // Escape slashes
        $route = str_replace('/', '\/', $route);
        
        // Add start and end anchors
        return '/^' . $route . '$/';
    }
    
    /**
     * Get all registered routes
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}