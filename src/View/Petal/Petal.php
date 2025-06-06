<?php

namespace Pluma\View\Petal;

/**
 * Petal Template Engine
 */
class Petal
{
    /**
     * The view path
     */
    protected string $viewPath;
    
    /**
     * The cache path
     */
    protected string $cachePath;
    
    /**
     * Whether to force compilation
     */
    protected bool $forceCompile;
    
    /**
     * The compiler instance
     */
    protected PetalCompiler $compiler;
    
    /**
     * The layout manager instance
     */
    protected PetalLayout $layout;
    
    /**
     * The shared data
     */
    protected array $shared = [];
    
    /**
     * Constructor
     */
    public function __construct(
        string $viewPath,
        string $cachePath = null,
        bool $forceCompile = true // Default to true to force compilation during development
    ) {
        $this->viewPath = rtrim($viewPath, '/');
        $this->cachePath = $cachePath ? rtrim($cachePath, '/') : $this->viewPath . '/cache';
        $this->forceCompile = $forceCompile;
        
        // Create the cache directory if it doesn't exist
        if (!is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0755, true);
        }
        
        // Create the compiler instance
        $this->compiler = new PetalCompiler();
        
        // Create the layout manager instance
        $this->layout = new PetalLayout($this);
    }
    
    /**
     * Render a template
     */
    public function render(string $template, array $data = [], bool $isLayout = false): string
    {
        // Merge the shared data with the template data
        $data = array_merge($this->shared, $data);
        
        // Get the compiled template path
        $compiledPath = $this->getCompiledPath($template);
        
        // Compile the template if necessary
        if ($this->shouldCompile($template, $compiledPath)) {
            $this->compile($template, $compiledPath);
        }
        
        // Extract the data
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include the compiled template
        include $compiledPath;
        
        // Get the contents of the buffer
        $content = ob_get_clean();
        
        // If a layout is set and this is not a layout being rendered, render the layout
        if ($this->layout->hasLayout() && !$isLayout) {
            // Store the current content in a section called 'content' if not already set
            if (!isset($this->layout->getSections()['content'])) {
                $this->layout->setSection('content', $content);
            }
            
            // Render the layout
            $content = $this->renderLayout($data);
        }
        
        return $content;
    }
    
    /**
     * Render a layout
     */
    protected function renderLayout(array $data = []): string
    {
        $layout = $this->layout->getLayout();
        
        // Reset the layout to prevent infinite recursion
        $currentLayout = $layout;
        $this->layout->setLayout(null);
        
        // Render the layout
        $content = $this->render($currentLayout, $data, true);
        
        // Restore the layout
        $this->layout->setLayout($currentLayout);
        
        return $content;
    }
    
    /**
     * Check if a template should be compiled
     */
    protected function shouldCompile(string $template, string $compiledPath): bool
    {
        // If force compile is enabled, always compile
        if ($this->forceCompile) {
            return true;
        }
        
        // If the compiled file doesn't exist, compile
        if (!file_exists($compiledPath)) {
            return true;
        }
        
        // Get the template path
        $templatePath = $this->getTemplatePath($template);
        
        // If the template file doesn't exist, throw an exception
        if (!file_exists($templatePath)) {
            throw new \Exception("Template {$template} not found");
        }
        
        // If the template file is newer than the compiled file, compile
        return filemtime($templatePath) > filemtime($compiledPath);
    }
    
    /**
     * Compile a template
     */
    protected function compile(string $template, string $compiledPath): void
    {
        // Get the template path
        $templatePath = $this->getTemplatePath($template);
        
        // Get the template content
        $content = file_get_contents($templatePath);
        
        // Compile the template
        $compiled = $this->compiler->compile($content);
        
        // Save the compiled template
        file_put_contents($compiledPath, $compiled);
    }
    
    /**
     * Get the template path
     */
    protected function getTemplatePath(string $template): string
    {
        // Replace dots with directory separators
        $template = str_replace('.', '/', $template);
        
        // Add the .petal.php extension if not present
        if (!preg_match('/\.petal\.php$/', $template)) {
            $template .= '.petal.php';
        }
        
        return $this->viewPath . '/' . $template;
    }
    
    /**
     * Get the compiled path
     */
    protected function getCompiledPath(string $template): string
    {
        // Replace dots with underscores
        $template = str_replace('.', '_', $template);
        
        // Add the .php extension
        $template .= '.php';
        
        return $this->cachePath . '/' . $template;
    }
    
    /**
     * Set the view path
     */
    public function setViewPath(string $viewPath): self
    {
        $this->viewPath = rtrim($viewPath, '/');
        
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
        $this->cachePath = rtrim($cachePath, '/');
        
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
     * Set whether to force compilation
     */
    public function setForceCompile(bool $forceCompile): self
    {
        $this->forceCompile = $forceCompile;
        
        return $this;
    }
    
    /**
     * Get whether to force compilation
     */
    public function getForceCompile(): bool
    {
        return $this->forceCompile;
    }
    
    /**
     * Get the compiler instance
     */
    public function getCompiler(): PetalCompiler
    {
        return $this->compiler;
    }
    
    /**
     * Get the layout manager instance
     */
    public function getLayout(): PetalLayout
    {
        return $this->layout;
    }
    
    /**
     * Share data with all templates
     */
    public function share(string $key, mixed $value): self
    {
        $this->shared[$key] = $value;
        
        return $this;
    }
    
    /**
     * Share multiple data with all templates
     */
    public function shareMany(array $data): self
    {
        $this->shared = array_merge($this->shared, $data);
        
        return $this;
    }
    
    /**
     * Get all shared data
     */
    public function getShared(): array
    {
        return $this->shared;
    }
    
    /**
     * Start a section
     */
    public function startSection(string $name): void
    {
        $this->layout->startSection($name);
    }
    
    /**
     * End a section
     */
    public function endSection(): void
    {
        $this->layout->endSection();
    }
    
    /**
     * Yield content from a section
     */
    public function yieldContent(string $name, string $default = ''): string
    {
        return $this->layout->yieldContent($name, $default);
    }
    
    /**
     * Set the parent layout
     */
    public function extends(string $layout): void
    {
        $this->layout->extends($layout);
    }
    
    /**
     * Render an included template
     */
    public function renderInclude(string $template, array $data = []): string
    {
        return $this->render($template, $data);
    }
    
    /**
     * Register a custom directive
     */
    public function directive(string $name, callable $handler): self
    {
        $this->compiler->customDirective($name, $handler);
        
        return $this;
    }
}