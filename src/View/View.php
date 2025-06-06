<?php

namespace Pluma\View;

use Pluma\View\Petal\Petal;

/**
 * View Class
 */
class View
{
    /**
     * The Petal template engine
     */
    protected ?Petal $petal = null;
    
    /**
     * Whether to use the Petal template engine
     */
    protected bool $usePetal = true;
    
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
        protected array $data = [],
        
        /**
         * The cache path
         */
        protected string $cachePath = ''
    ) {
        $this->viewPath = $viewPath ?: PLUMA_ROOT . '/resources/views';
        $this->cachePath = $cachePath ?: PLUMA_ROOT . '/storage/framework/views';
        
        // Create the cache directory if it doesn't exist
        if (!is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0755, true);
        }
    }
    
    /**
     * Set the view path
     */
    public function setViewPath(string $viewPath): self
    {
        $this->viewPath = $viewPath;
        
        // Update the Petal engine if it exists
        if ($this->petal !== null) {
            $this->petal->setViewPath($viewPath);
        }
        
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
     * Set the cache path
     */
    public function setCachePath(string $cachePath): self
    {
        $this->cachePath = $cachePath;
        
        // Update the Petal engine if it exists
        if ($this->petal !== null) {
            $this->petal->setCachePath($cachePath);
        }
        
        return $this;
    }
    
    /**
     * Get the cache path
     */
    public function getCachePath(): string
    {
        return $this->cachePath;
    }
    
    /**
     * Set whether to use the Petal template engine
     */
    public function usePetal(bool $usePetal): self
    {
        $this->usePetal = $usePetal;
        
        return $this;
    }
    
    /**
     * Get whether to use the Petal template engine
     */
    public function getUsePetal(): bool
    {
        return $this->usePetal;
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
        
        // If using Petal, render with Petal
        if ($this->usePetal && $this->isPetalView($view)) {
            return $this->renderPetal($view, $data);
        }
        
        // Otherwise, render with the standard PHP renderer
        return $this->renderPhp($view, $data);
    }
    
    /**
     * Render a view with the Petal template engine
     */
    protected function renderPetal(string $view, array $data = []): string
    {
        // Get the template path
        $templatePath = $this->getPetalViewFile($view);
        
        // Get the template content
        $content = file_get_contents($templatePath);
        
        // Simple template compilation
        $content = $this->compileTemplate($content, $data);
        
        return $content;
    }
    
    /**
     * Simple template compilation
     */
    protected function compileTemplate(string $content, array $data): string
    {
        // Extract the data
        extract($data);
        
        // Replace {{ $var }} with the variable value
        $content = preg_replace_callback('/\{\{\s*\$([a-zA-Z0-9_]+)(?:\[\'([a-zA-Z0-9_]+)\'\])?\s*\}\}/s', function($matches) use ($data) {
            $var = $matches[1];
            
            if (isset($matches[2])) {
                // Handle array access like $feature['title']
                $key = $matches[2];
                return isset(${$var}[$key]) ? htmlspecialchars(${$var}[$key]) : '';
            } else {
                // Handle simple variables like $title
                return isset(${$var}) ? htmlspecialchars(${$var}) : '';
            }
        }, $content);
        
        // Replace {!! $var !!} with the raw variable value
        $content = preg_replace_callback('/\{!!\s*\$([a-zA-Z0-9_]+)(?:\[\'([a-zA-Z0-9_]+)\'\])?\s*!!\}/s', function($matches) use ($data) {
            $var = $matches[1];
            
            if (isset($matches[2])) {
                // Handle array access like $feature['title']
                $key = $matches[2];
                return isset(${$var}[$key]) ? ${$var}[$key] : '';
            } else {
                // Handle simple variables like $title
                return isset(${$var}) ? ${$var} : '';
            }
        }, $content);
        
        // Process @if, @foreach, etc. directives
        $content = $this->compileDirectives($content);
        
        return $content;
    }
    
    /**
     * Compile directives
     */
    protected function compileDirectives(string $content): string
    {
        // @if directive
        $content = preg_replace('/@if\s*\((.*?)\)/s', '<?php if($1): ?>', $content);
        $content = preg_replace('/@elseif\s*\((.*?)\)/s', '<?php elseif($1): ?>', $content);
        $content = preg_replace('/@else/s', '<?php else: ?>', $content);
        $content = preg_replace('/@endif/s', '<?php endif; ?>', $content);
        
        // @foreach directive
        $content = preg_replace('/@foreach\s*\((.*?)\)/s', '<?php foreach($1): ?>', $content);
        $content = preg_replace('/@endforeach/s', '<?php endforeach; ?>', $content);
        
        // @for directive
        $content = preg_replace('/@for\s*\((.*?)\)/s', '<?php for($1): ?>', $content);
        $content = preg_replace('/@endfor/s', '<?php endfor; ?>', $content);
        
        // @while directive
        $content = preg_replace('/@while\s*\((.*?)\)/s', '<?php while($1): ?>', $content);
        $content = preg_replace('/@endwhile/s', '<?php endwhile; ?>', $content);
        
        // @php directive
        $content = preg_replace('/@php/s', '<?php', $content);
        $content = preg_replace('/@endphp/s', '?>', $content);
        
        // Start output buffering
        ob_start();
        
        // Evaluate the template
        eval('?>' . $content);
        
        // Get the contents of the buffer
        return ob_get_clean();
    }
    
    /**
     * Render a view with the standard PHP renderer
     */
    protected function renderPhp(string $view, array $data = []): string
    {
        // Get the view file path
        $viewFile = $this->getPhpViewFile($view);
        
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
     * Get the PHP view file path
     */
    protected function getPhpViewFile(string $view): string
    {
        // Replace dots with directory separators
        $view = str_replace('.', DIRECTORY_SEPARATOR, $view);
        
        // Add the .php extension if not present
        if (!str_ends_with($view, '.php')) {
            $view .= '.php';
        }
        
        return $this->viewPath . DIRECTORY_SEPARATOR . $view;
    }
    
    /**
     * Get the Petal view file path
     */
    protected function getPetalViewFile(string $view): string
    {
        // Replace dots with directory separators
        $view = str_replace('.', DIRECTORY_SEPARATOR, $view);
        
        // Add the .petal.php extension if not present
        if (!str_ends_with($view, '.petal.php')) {
            $view .= '.petal.php';
        }
        
        return $this->viewPath . DIRECTORY_SEPARATOR . $view;
    }
    
    /**
     * Check if a view is a Petal view
     */
    protected function isPetalView(string $view): bool
    {
        // If the view has a .petal.php extension, it's a Petal view
        if (str_ends_with($view, '.petal.php')) {
            return true;
        }
        
        // Check if a Petal view file exists
        $petalViewPath = $this->getPetalViewFile($view);
        
        return file_exists($petalViewPath);
    }
    
    /**
     * Get the Petal template engine
     */
    public function getPetal(): Petal
    {
        // Create the Petal engine if it doesn't exist
        if ($this->petal === null) {
            $this->petal = new Petal($this->viewPath, $this->cachePath);
        }
        
        return $this->petal;
    }
    
    /**
     * Register a custom directive
     */
    public function directive(string $name, callable $handler): self
    {
        $this->getPetal()->directive($name, $handler);
        
        return $this;
    }
    
    /**
     * Share data with all templates
     */
    public function share(string $key, mixed $value): self
    {
        $this->data[$key] = $value;
        
        // Share with Petal if it exists
        if ($this->petal !== null) {
            $this->petal->share($key, $value);
        }
        
        return $this;
    }
    
    /**
     * Share multiple data with all templates
     */
    public function shareMany(array $data): self
    {
        $this->data = array_merge($this->data, $data);
        
        // Share with Petal if it exists
        if ($this->petal !== null) {
            $this->petal->shareMany($data);
        }
        
        return $this;
    }
}