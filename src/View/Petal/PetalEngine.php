<?php

namespace Pluma\View\Petal;

/**
 * Petal Template Engine
 */
class PetalEngine
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
     * The compiled templates
     */
    protected array $compiled = [];
    
    /**
     * The template directives
     */
    protected array $directives = [];
    
    /**
     * Constructor
     */
    public function __construct(
        string $viewPath,
        string $cachePath = null,
        bool $forceCompile = false
    ) {
        $this->viewPath = rtrim($viewPath, '/');
        $this->cachePath = $cachePath ? rtrim($cachePath, '/') : $this->viewPath . '/cache';
        $this->forceCompile = $forceCompile;
        
        // Create the cache directory if it doesn't exist
        if (!is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0755, true);
        }
        
        // Register default directives
        $this->registerDefaultDirectives();
    }
    
    /**
     * Register default directives
     */
    protected function registerDefaultDirectives(): void
    {
        // Echo directive: {{ $var }}
        $this->directive('{{', function ($expression) {
            return "<?php echo htmlspecialchars($expression, ENT_QUOTES, 'UTF-8'); ?>";
        });
        
        // Raw echo directive: {!! $var !!}
        $this->directive('{!!', function ($expression) {
            return "<?php echo $expression; ?>";
        });
        
        // Comment directive: {# comment #}
        $this->directive('{#', function ($expression) {
            return '';
        });
        
        // If directive: @if($condition)
        $this->directive('@if', function ($expression) {
            return "<?php if($expression): ?>";
        });
        
        // Else directive: @else
        $this->directive('@else', function ($expression) {
            return "<?php else: ?>";
        });
        
        // Elseif directive: @elseif($condition)
        $this->directive('@elseif', function ($expression) {
            return "<?php elseif($expression): ?>";
        });
        
        // Endif directive: @endif
        $this->directive('@endif', function ($expression) {
            return "<?php endif; ?>";
        });
        
        // For directive: @for($i = 0; $i < 10; $i++)
        $this->directive('@for', function ($expression) {
            return "<?php for($expression): ?>";
        });
        
        // Endfor directive: @endfor
        $this->directive('@endfor', function ($expression) {
            return "<?php endfor; ?>";
        });
        
        // Foreach directive: @foreach($items as $item)
        $this->directive('@foreach', function ($expression) {
            return "<?php foreach($expression): ?>";
        });
        
        // Endforeach directive: @endforeach
        $this->directive('@endforeach', function ($expression) {
            return "<?php endforeach; ?>";
        });
        
        // While directive: @while($condition)
        $this->directive('@while', function ($expression) {
            return "<?php while($expression): ?>";
        });
        
        // Endwhile directive: @endwhile
        $this->directive('@endwhile', function ($expression) {
            return "<?php endwhile; ?>";
        });
        
        // Include directive: @include('view.name', ['var' => 'value'])
        $this->directive('@include', function ($expression) {
            return "<?php echo \$this->render($expression); ?>";
        });
        
        // Extends directive: @extends('layout.name')
        $this->directive('@extends', function ($expression) {
            return "<?php \$this->extends($expression); ?>";
        });
        
        // Section directive: @section('name')
        $this->directive('@section', function ($expression) {
            return "<?php \$this->startSection($expression); ?>";
        });
        
        // End section directive: @endsection
        $this->directive('@endsection', function ($expression) {
            return "<?php \$this->endSection(); ?>";
        });
        
        // Yield directive: @yield('name')
        $this->directive('@yield', function ($expression) {
            return "<?php echo \$this->yieldContent($expression); ?>";
        });
        
        // PHP directive: @php
        $this->directive('@php', function ($expression) {
            return "<?php $expression ?>";
        });
        
        // End PHP directive: @endphp
        $this->directive('@endphp', function ($expression) {
            return "<?php ?>";
        });
    }
    
    /**
     * Register a directive
     */
    public function directive(string $pattern, callable $handler): self
    {
        $this->directives[$pattern] = $handler;
        
        return $this;
    }
    
    /**
     * Render a template
     */
    public function render(string $template, array $data = []): string
    {
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
        return ob_get_clean();
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
        $compiled = $this->compileString($content);
        
        // Save the compiled template
        file_put_contents($compiledPath, $compiled);
    }
    
    /**
     * Compile a string
     */
    protected function compileString(string $content): string
    {
        // Apply directives
        foreach ($this->directives as $pattern => $handler) {
            $content = $this->compileDirective($content, $pattern, $handler);
        }
        
        return $content;
    }
    
    /**
     * Compile a directive
     */
    protected function compileDirective(string $content, string $pattern, callable $handler): string
    {
        // Escape the pattern for use in a regular expression
        $escapedPattern = preg_quote($pattern, '/');
        
        // If the pattern is {{ or {!! or {#, compile it differently
        if (in_array($pattern, ['{{', '{!!', '{#'])) {
            $endPattern = substr($pattern, 0, 1) . '}';
            $escapedEndPattern = preg_quote($endPattern, '/');
            
            return preg_replace_callback(
                "/{$escapedPattern}(.*?){$escapedEndPattern}/s",
                function ($matches) use ($handler) {
                    return $handler(trim($matches[1]));
                },
                $content
            );
        }
        
        // Otherwise, compile it as a directive
        return preg_replace_callback(
            "/{$escapedPattern}(.*?)(\r?\n|$)/s",
            function ($matches) use ($handler) {
                return $handler(trim($matches[1])) . $matches[2];
            },
            $content
        );
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
        // Replace dots with directory separators
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
}