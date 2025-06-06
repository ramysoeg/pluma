<?php

namespace Pluma\Http;

/**
 * HTTP Request Class
 */
class Request
{
    /**
     * The request query parameters ($_GET)
     */
    protected array $query = [];
    
    /**
     * The request body parameters ($_POST)
     */
    protected array $request = [];
    
    /**
     * The request cookies ($_COOKIE)
     */
    protected array $cookies = [];
    
    /**
     * The request files ($_FILES)
     */
    protected array $files = [];
    
    /**
     * The request server parameters ($_SERVER)
     */
    protected array $server = [];
    
    /**
     * The request headers
     */
    protected array $headers = [];
    
    /**
     * The request method
     */
    protected string $method = 'GET';
    
    /**
     * The request URI
     */
    protected string $uri = '/';
    
    /**
     * The request path
     */
    protected string $path = '/';
    
    /**
     * Request constructor
     */
    public function __construct(
        array $query = [],
        array $request = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        array $headers = [],
        string $method = 'GET',
        string $uri = '/',
        string $path = '/'
    ) {
        // Initialize with superglobals if not provided
        $this->query = $query ?: $_GET ?? [];
        $this->request = $request ?: $_POST ?? [];
        $this->cookies = $cookies ?: $_COOKIE ?? [];
        $this->files = $files ?: $_FILES ?? [];
        $this->server = $server ?: $_SERVER ?? [];
        
        // Extract headers, method, URI, and path from server parameters
        $this->headers = $headers ?: $this->extractHeaders();
        $this->method = $method !== 'GET' ? $method : $this->extractMethod();
        $this->uri = $uri !== '/' ? $uri : $this->extractUri();
        $this->path = $path !== '/' ? $path : $this->extractPath();
    }
    
    /**
     * Extract all headers from the request
     */
    protected function extractHeaders(): array
    {
        $headers = [];
        
        foreach ($this->server as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
                $headers[$name] = $value;
            }
        }
        
        return $headers;
    }
    
    /**
     * Extract the request method
     */
    protected function extractMethod(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }
    
    /**
     * Extract the request URI
     */
    protected function extractUri(): string
    {
        return $this->server['REQUEST_URI'] ?? '/';
    }
    
    /**
     * Extract the request path
     */
    protected function extractPath(): string
    {
        $uri = $this->extractUri();
        $position = strpos($uri, '?');
        
        if ($position !== false) {
            return substr($uri, 0, $position);
        }
        
        return $uri;
    }
    
    /**
     * Get a query parameter
     */
    public function query(string $key, mixed $default = null): mixed
    {
        return $this->query[$key] ?? $default;
    }
    
    /**
     * Get all query parameters
     */
    public function getQueryParams(): array
    {
        return $this->query;
    }
    
    /**
     * Get a request body parameter
     */
    public function input(string $key, mixed $default = null): mixed
    {
        return $this->request[$key] ?? $default;
    }
    
    /**
     * Get all request body parameters
     */
    public function all(): array
    {
        return $this->request;
    }
    
    /**
     * Get a cookie
     */
    public function cookie(string $key, mixed $default = null): mixed
    {
        return $this->cookies[$key] ?? $default;
    }
    
    /**
     * Get a header
     */
    public function header(string $key, mixed $default = null): mixed
    {
        return $this->headers[$key] ?? $default;
    }
    
    /**
     * Get the request method
     */
    public function getRequestMethod(): string
    {
        return $this->method;
    }
    
    /**
     * Get the request path
     */
    public function getRequestPath(): string
    {
        return $this->path;
    }
    
    /**
     * Check if the request is an AJAX request
     */
    public function isAjax(): bool
    {
        return $this->header('X-Requested-With') === 'XMLHttpRequest';
    }
    
    /**
     * Check if the request is a JSON request
     */
    public function isJson(): bool
    {
        return str_contains($this->header('Content-Type', ''), 'application/json');
    }
    
    /**
     * Get the JSON body of the request
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