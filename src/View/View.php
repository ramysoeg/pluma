<?php

namespace Pluma\View;

/**
 * View Class
 */
class View
{
    /**
     * View constructor
     */
    public function __construct(
        /**
         * The view path
         */
        protected string $viewPath = '',
        
        /**
         * The view data
         */
        protected array $data = []
    ) {
        $this->viewPath = $viewPath ?: PLUMA_ROOT . '/resources/views';
    }
    
    /**
     * Set the view path
     */
    public function setViewPath(string $viewPath): self
    {
        $this->viewPath = $viewPath;
        
        return $this;
    }
    
    /**
     * Get the view path
     */
    public function getViewPath(): string
    {
        return $this->viewPath;
    }
    
    /**
     * Set view data
     */
    public function set(string $key, mixed $value): self
    {
        $this->data[$key] = $value;
        
        return $this;
    }
    
    /**
     * Set multiple view data
     */
    public function setData(array $data): self
    {
        $this->data = array_merge($this->data, $data);
        
        return $this;
    }
    
    /**
     * Get view data
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }
    
    /**
     * Get all view data
     */
    public function getData(): array
    {
        return $this->data;
    }
    
    /**
     * Render a view
     */
    public function render(string $view, array $data = []): string
    {
        // Merge the data
        $data = array_merge($this->data, $data);
        
        // Get the view file path
        $viewFile = $this->getViewFile($view);
        
        if (!file_exists($viewFile)) {
            throw new \Exception("View {$view} not found");
        }
        
        // Extract the data to make it available in the view
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        include $viewFile;
        
        // Get the contents of the buffer
        return ob_get_clean() ?: '';
    }
    
    /**
     * Get the view file path
     */
    protected function getViewFile(string $view): string
    {
        // Replace dots with directory separators
        $view = str_replace('.', DIRECTORY_SEPARATOR, $view);
        
        // Add the .php extension if not present
        if (!str_ends_with($view, '.php')) {
            $view .= '.php';
        }
        
        return $this->viewPath . DIRECTORY_SEPARATOR . $view;
    }
}