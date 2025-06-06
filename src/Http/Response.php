<?php

namespace Pluma\Http;

/**
 * HTTP Response Class
 */
class Response
{
    /**
     * Response constructor
     */
    public function __construct(
        /**
         * The HTTP status code
         */
        protected int $statusCode = 200,
        
        /**
         * The response headers
         */
        protected array $headers = [],
        
        /**
         * The response content
         */
        protected string $content = ''
    ) {
    }
    
    /**
     * Set the HTTP status code
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        
        return $this;
    }
    
    /**
     * Get the HTTP status code
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    
    /**
     * Set a response header
     */
    public function setHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        
        return $this;
    }
    
    /**
     * Set multiple response headers
     */
    public function setHeaders(array $headers): self
    {
        foreach ($headers as $name => $value) {
            $this->setHeader($name, (string)$value);
        }
        
        return $this;
    }
    
    /**
     * Get all response headers
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
    
    /**
     * Set the response content
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        
        return $this;
    }
    
    /**
     * Get the response content
     */
    public function getContent(): string
    {
        return $this->content;
    }
    
    /**
     * Send the response
     */
    public function send(mixed $content = null): never
    {
        if ($content !== null) {
            if (is_array($content) || is_object($content)) {
                $this->json($content);
            } else {
                $this->setContent((string) $content);
            }
        }
        
        // Send the HTTP status code
        http_response_code($this->statusCode);
        
        // Send the headers
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }
        
        // Send the content
        echo $this->content;
        
        // End the script
        exit;
    }
    
    /**
     * Send a JSON response
     */
    public function json(mixed $data, ?int $statusCode = null): never
    {
        if ($statusCode !== null) {
            $this->setStatusCode($statusCode);
        }
        
        $this->setHeader('Content-Type', 'application/json');
        $this->setContent(json_encode($data, 
            JSON_THROW_ON_ERROR | 
            JSON_UNESCAPED_UNICODE | 
            JSON_UNESCAPED_SLASHES
        ));
        
        $this->send();
    }
    
    /**
     * Redirect to a URL
     */
    public function redirect(string $url, int $statusCode = 302): never
    {
        $this->setStatusCode($statusCode);
        $this->setHeader('Location', $url);
        
        $this->send();
    }
    
    /**
     * Send a file as the response
     */
    public function file(string $path, ?string $filename = null, ?string $mimeType = null): never
    {
        if (!file_exists($path)) {
            $this->setStatusCode(404);
            $this->send('File not found');
        }
        
        $filename ??= basename($path);
        $mimeType ??= mime_content_type($path) ?: 'application/octet-stream';
        
        $this->setHeader('Content-Type', $mimeType);
        $this->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $this->setHeader('Content-Length', (string) filesize($path));
        
        readfile($path);
        exit;
    }
}