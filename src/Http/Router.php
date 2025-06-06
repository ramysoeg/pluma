<?php

namespace Pluma\Http;

/**
 * HTTP Router Class
 */
class Router
{
    /**
     * @var array The registered routes
     */
    protected array $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'PATCH' => [],
        'DELETE' => [],
    ];
    
    /**
     * Register a GET route
     * 
     * @param string $path The route path
     * @param mixed $handler The route handler
     * @return self
     */
    public function get(string $path, $handler): self
    {
        return $this->addRoute('GET', $path, $handler);
    }
    
    /**
     * Register a POST route
     * 
     * @param string $path The route path
     * @param mixed $handler The route handler
     * @return self
     */
    public function post(string $path, $handler): self
    {
        return $this->addRoute('POST', $path, $handler);
    }
    
    /**
     * Register a PUT route
     * 
     * @param string $path The route path
     * @param mixed $handler The route handler
     * @return self
     */
    public function put(string $path, $handler): self
    {
        return $this->addRoute('PUT', $path, $handler);
    }
    
    /**
     * Register a PATCH route
     * 
     * @param string $path The route path
     * @param mixed $handler The route handler
     * @return self
     */
    public function patch(string $path, $handler): self
    {
        return $this->addRoute('PATCH', $path, $handler);
    }
    
    /**
     * Register a DELETE route
     * 
     * @param string $path The route path
     * @param mixed $handler The route handler
     * @return self
     */
    public function delete(string $path, $handler): self
    {
        return $this->addRoute('DELETE', $path, $handler);
    }
    
    /**
     * Register a route for multiple HTTP methods
     * 
     * @param array $methods The HTTP methods
     * @param string $path The route path
     * @param mixed $handler The route handler
     * @return self
     */
    public function match(array $methods, string $path, $handler): self
    {
        foreach ($methods as $method) {
            $this->addRoute(strtoupper($method), $path, $handler);
        }
        
        return $this;
    }
    
    /**
     * Register a route for all HTTP methods
     * 
     * @param string $path The route path
     * @param mixed $handler The route handler
     * @return self
     */
    public function any(string $path, $handler): self
    {
        return $this->match(['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], $path, $handler);
    }
    
    /**
     * Add a route to the router
     * 
     * @param string $method The HTTP method
     * @param string $path The route path
     * @param mixed $handler The route handler
     * @return self
     */
    protected function addRoute(string $method, string $path, $handler): self
    {
        $this->routes[$method][$path] = [
            'path' => $path,
            'handler' => $handler,
        ];
        
        return $this;
    }
    
    /**
     * Resolve a route from a request
     * 
     * @param Request $request The request to resolve
     * @return array|null The resolved route or null if no route matches
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
     * 
     * @param string $route The route path
     * @return string The regular expression
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
     * 
     * @return array The registered routes
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}