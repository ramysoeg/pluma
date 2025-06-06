<?php

namespace Pluma\View\Petal;

/**
 * Petal Layout Manager
 */
class PetalLayout
{
    /**
     * The sections
     */
    protected array $sections = [];
    
    /**
     * The current section being rendered
     */
    protected ?string $currentSection = null;
    
    /**
     * The parent layout
     */
    protected ?string $layout = null;
    
    /**
     * The engine instance
     */
    protected $engine;
    
    /**
     * Constructor
     */
    public function __construct($engine)
    {
        $this->engine = $engine;
    }
    
    /**
     * Start a section
     */
    public function startSection(string $name): void
    {
        $this->currentSection = $name;
        
        // Start output buffering
        ob_start();
    }
    
    /**
     * End a section
     */
    public function endSection(): void
    {
        if ($this->currentSection === null) {
            throw new \Exception('No section started');
        }
        
        // Get the contents of the buffer
        $content = ob_get_clean();
        
        // Store the section
        $this->sections[$this->currentSection] = $content;
        
        // Reset the current section
        $this->currentSection = null;
    }
    
    /**
     * Yield content from a section
     */
    public function yieldContent(string $name, string $default = ''): string
    {
        return $this->sections[$name] ?? $default;
    }
    
    /**
     * Set the parent layout
     */
    public function extends(string $layout): void
    {
        $this->layout = $layout;
    }
    
    /**
     * Get the parent layout
     */
    public function getLayout(): ?string
    {
        return $this->layout;
    }
    
    /**
     * Set the parent layout
     */
    public function setLayout(?string $layout): void
    {
        $this->layout = $layout;
    }
    
    /**
     * Check if a layout is set
     */
    public function hasLayout(): bool
    {
        return $this->layout !== null;
    }
    
    /**
     * Get all sections
     */
    public function getSections(): array
    {
        return $this->sections;
    }
    
    /**
     * Set a section directly
     */
    public function setSection(string $name, string $content): void
    {
        $this->sections[$name] = $content;
    }
    
    /**
     * Render the layout
     */
    public function renderLayout(array $data = []): string
    {
        if (!$this->hasLayout()) {
            return '';
        }
        
        // Merge the sections with the data
        $data['__sections'] = $this->sections;
        
        // Render the layout
        return $this->engine->render($this->layout, $data);
    }
}