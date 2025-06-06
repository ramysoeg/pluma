<?php

namespace Pluma\View;

/**
 * View Class
 */
class View
{
    /**
     * @var string The view path
     */
    protected string $viewPath;
    
    /**
     * @var array The view data
     */
    protected array $data = [];
    
    /**
     * View constructor
     * 
     * @param string $viewPath The path to the views directory
     */
    public function __construct(string $viewPath = null)
    {
        $this->viewPath = $viewPath ?? PLUMA_ROOT . '/resources/views';
    }
    
    /**
     * Set the view path
     * 
     * @param string $viewPath The path to the views directory
     * @return self
     */
    public function setViewPath(string $viewPath): self
    {
        $this->viewPath = $viewPath;
        
        return $this;
    }
    
    /**
     * Get the view path
     * 
     * @return string The path to the views directory
     */
    public function getViewPath(): string
    {
        return $this->viewPath;
    }
    
    /**
     * Set view data
     * 
     * @param string $key The data key
     * @param mixed $value The data value
     * @return self
     */
    public function set(string $key, $value): self
    {
        $this->data[$key] = $value;
        
        return $this;
    }
    
    /**
     * Set multiple view data
     * 
     * @param array $data The data to set
     * @return self
     */
    public function setData(array $data): self
    {
        $this->data = array_merge($this->data, $data);
        
        return $this;
    }
    
    /**
     * Get view data
     * 
     * @param string $key The data key
     * @param mixed $default The default value if the data doesn't exist
     * @return mixed The data value
     */
    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }
    
    /**
     * Get all view data
     * 
     * @return array All view data
     */
    public function getData(): array
    {
        return $this->data;
    }
    
    /**
     * Render a view
     * 
     * @param string $view The view name
     * @param array $data The view data
     * @return string The rendered view
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
        return ob_get_clean();
    }
    
    /**
     * Get the view file path
     * 
     * @param string $view The view name
     * @return string The view file path
     */
    protected function getViewFile(string $view): string
    {
        // Replace dots with directory separators
        $view = str_replace('.', DIRECTORY_SEPARATOR, $view);
        
        // Add the .php extension if not present
        if (!preg_match('/\.php$/', $view)) {
            $view .= '.php';
        }
        
        return $this->viewPath . DIRECTORY_SEPARATOR . $view;
    }
}