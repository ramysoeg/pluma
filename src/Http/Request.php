<?php

namespace Pluma\Http;

/**
 * HTTP Request Class
 */
class Request
{
    /**
     * @var array The request query parameters ($_GET)
     */
    protected array $query;
    
    /**
     * @var array The request body parameters ($_POST)
     */
    protected array $request;
    
    /**
     * @var array The request cookies ($_COOKIE)
     */
    protected array $cookies;
    
    /**
     * @var array The request files ($_FILES)
     */
    protected array $files;
    
    /**
     * @var array The request server parameters ($_SERVER)
     */
    protected array $server;
    
    /**
     * @var array The request headers
     */
    protected array $headers;
    
    /**
     * @var string The request method
     */
    protected string $method;
    
    /**
     * @var string The request URI
     */
    protected string $uri;
    
    /**
     * @var string The request path
     */
    protected string $path;
    
    /**
     * Request constructor
     */
    public function __construct()
    {
        $this->query = $_GET ?? [];
        $this->request = $_POST ?? [];
        $this->cookies = $_COOKIE ?? [];
        $this->files = $_FILES ?? [];
        $this->server = $_SERVER ?? [];
        
        $this->headers = $this->getHeaders();
        $this->method = $this->getMethod();
        $this->uri = $this->getUri();
        $this->path = $this->getPath();
    }
    
    /**
     * Get all headers from the request
     * 
     * @return array The request headers
     */
    protected function getHeaders(): array
    {
        $headers = [];
        
        foreach ($this->server as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
                $headers[$name] = $value;
            }
        }
        
        return $headers;
    }
    
    /**
     * Get the request method
     * 
     * @return string The request method
     */
    protected function getMethod(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }
    
    /**
     * Get the request URI
     * 
     * @return string The request URI
     */
    protected function getUri(): string
    {
        return $this->server['REQUEST_URI'] ?? '/';
    }
    
    /**
     * Get the request path
     * 
     * @return string The request path
     */
    protected function getPath(): string
    {
        $uri = $this->getUri();
        $position = strpos($uri, '?');
        
        if ($position !== false) {
            return substr($uri, 0, $position);
        }
        
        return $uri;
    }
    
    /**
     * Get a query parameter
     * 
     * @param string $key The parameter key
     * @param mixed $default The default value if the parameter doesn't exist
     * @return mixed The parameter value
     */
    public function query(string $key, $default = null)
    {
        return $this->query[$key] ?? $default;
    }
    
    /**
     * Get all query parameters
     * 
     * @return array All query parameters
     */
    public function getQueryParams(): array
    {
        return $this->query;
    }
    
    /**
     * Get a request body parameter
     * 
     * @param string $key The parameter key
     * @param mixed $default The default value if the parameter doesn't exist
     * @return mixed The parameter value
     */
    public function input(string $key, $default = null)
    {
        return $this->request[$key] ?? $default;
    }
    
    /**
     * Get all request body parameters
     * 
     * @return array All request body parameters
     */
    public function all(): array
    {
        return $this->request;
    }
    
    /**
     * Get a cookie
     * 
     * @param string $key The cookie key
     * @param mixed $default The default value if the cookie doesn't exist
     * @return mixed The cookie value
     */
    public function cookie(string $key, $default = null)
    {
        return $this->cookies[$key] ?? $default;
    }
    
    /**
     * Get a header
     * 
     * @param string $key The header key
     * @param mixed $default The default value if the header doesn't exist
     * @return mixed The header value
     */
    public function header(string $key, $default = null)
    {
        return $this->headers[$key] ?? $default;
    }
    
    /**
     * Get the request method
     * 
     * @return string The request method
     */
    public function getRequestMethod(): string
    {
        return $this->method;
    }
    
    /**
     * Get the request path
     * 
     * @return string The request path
     */
    public function getRequestPath(): string
    {
        return $this->path;
    }
    
    /**
     * Check if the request is an AJAX request
     * 
     * @return bool Whether the request is an AJAX request
     */
    public function isAjax(): bool
    {
        return $this->header('X-Requested-With') === 'XMLHttpRequest';
    }
    
    /**
     * Check if the request is a JSON request
     * 
     * @return bool Whether the request is a JSON request
     */
    public function isJson(): bool
    {
        return strpos($this->header('Content-Type', ''), 'application/json') !== false;
    }
    
    /**
     * Get the JSON body of the request
     * 
     * @return array The JSON body
     */
    public function json(): array
    {
        if (!$this->isJson()) {
            return [];
        }
        
        $content = file_get_contents('php://input');
        
        return json_decode($content, true) ?? [];
    }
}