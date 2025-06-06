<?php

namespace Pluma\Http;

/**
 * HTTP Response Class
 */
class Response
{
    /**
     * @var int The HTTP status code
     */
    protected int $statusCode = 200;
    
    /**
     * @var array The response headers
     */
    protected array $headers = [];
    
    /**
     * @var string The response content
     */
    protected string $content = '';
    
    /**
     * Set the HTTP status code
     * 
     * @param int $statusCode The HTTP status code
     * @return self
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        
        return $this;
    }
    
    /**
     * Get the HTTP status code
     * 
     * @return int The HTTP status code
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    
    /**
     * Set a response header
     * 
     * @param string $name The header name
     * @param string $value The header value
     * @return self
     */
    public function setHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        
        return $this;
    }
    
    /**
     * Set multiple response headers
     * 
     * @param array $headers The headers to set
     * @return self
     */
    public function setHeaders(array $headers): self
    {
        foreach ($headers as $name => $value) {
            $this->setHeader($name, $value);
        }
        
        return $this;
    }
    
    /**
     * Get all response headers
     * 
     * @return array The response headers
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
    
    /**
     * Set the response content
     * 
     * @param string $content The response content
     * @return self
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        
        return $this;
    }
    
    /**
     * Get the response content
     * 
     * @return string The response content
     */
    public function getContent(): string
    {
        return $this->content;
    }
    
    /**
     * Send the response
     * 
     * @param mixed $content The response content
     * @return void
     */
    public function send($content = null): void
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
     * 
     * @param mixed $data The data to encode as JSON
     * @param int $statusCode The HTTP status code
     * @return void
     */
    public function json($data, int $statusCode = null): void
    {
        if ($statusCode !== null) {
            $this->setStatusCode($statusCode);
        }
        
        $this->setHeader('Content-Type', 'application/json');
        $this->setContent(json_encode($data));
        
        $this->send();
    }
    
    /**
     * Redirect to a URL
     * 
     * @param string $url The URL to redirect to
     * @param int $statusCode The HTTP status code
     * @return void
     */
    public function redirect(string $url, int $statusCode = 302): void
    {
        $this->setStatusCode($statusCode);
        $this->setHeader('Location', $url);
        
        $this->send();
    }
    
    /**
     * Send a file as the response
     * 
     * @param string $path The path to the file
     * @param string $filename The filename to send
     * @param string $mimeType The MIME type of the file
     * @return void
     */
    public function file(string $path, string $filename = null, string $mimeType = null): void
    {
        if (!file_exists($path)) {
            $this->setStatusCode(404);
            $this->send('File not found');
        }
        
        if ($filename === null) {
            $filename = basename($path);
        }
        
        if ($mimeType === null) {
            $mimeType = mime_content_type($path) ?: 'application/octet-stream';
        }
        
        $this->setHeader('Content-Type', $mimeType);
        $this->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $this->setHeader('Content-Length', (string) filesize($path));
        
        readfile($path);
        exit;
    }
}