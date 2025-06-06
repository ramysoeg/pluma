<?php

namespace Pluma\View\Petal;

/**
 * Petal Template Compiler
 */
class PetalCompiler
{
    /**
     * The template directives
     */
    protected array $directives = [];
    
    /**
     * The custom directives
     */
    protected array $customDirectives = [];
    
    /**
     * Constructor
     */
    public function __construct()
    {
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
            return "<?php echo \$this->renderInclude($expression); ?>";
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
            return "<?php";
        });
        
        // End PHP directive: @endphp
        $this->directive('@endphp', function ($expression) {
            return "?>";
        });
        
        // Loop variable directive: @loop('variable')
        $this->directive('@loop', function ($expression) {
            return "<?php \$loop = (object) [
                'index' => \$__loop_index ?? 0,
                'iteration' => (\$__loop_index ?? 0) + 1,
                'first' => (\$__loop_index ?? 0) === 0,
                'last' => (\$__loop_index ?? 0) === (\$__loop_count ?? 1) - 1,
                'count' => \$__loop_count ?? 1,
            ]; ?>";
        });
        
        // CSRF directive: @csrf
        $this->directive('@csrf', function ($expression) {
            return "<?php echo '<input type=\"hidden\" name=\"_token\" value=\"' . htmlspecialchars(\$_SESSION['_token'] ?? '', ENT_QUOTES, 'UTF-8') . '\">'; ?>";
        });
        
        // Method directive: @method('PUT')
        $this->directive('@method', function ($expression) {
            return "<?php echo '<input type=\"hidden\" name=\"_method\" value=\"' . htmlspecialchars($expression, ENT_QUOTES, 'UTF-8') . '\">'; ?>";
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
     * Register a custom directive
     */
    public function customDirective(string $name, callable $handler): self
    {
        $this->customDirectives[$name] = $handler;
        
        return $this;
    }
    
    /**
     * Compile a template
     */
    public function compile(string $content): string
    {
        // Apply custom directives
        foreach ($this->customDirectives as $name => $handler) {
            $content = preg_replace_callback(
                "/@{$name}(?:\s*\((.*?)\))?(\r?\n|$)/s",
                function ($matches) use ($handler) {
                    $expression = isset($matches[1]) ? trim($matches[1]) : '';
                    return $handler($expression) . ($matches[2] ?? '');
                },
                $content
            );
        }
        
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
        
        // Skip compilation of directives inside <pre><code> blocks
        if (in_array($pattern, ['@php', '@endphp'])) {
            $parts = preg_split('/(<pre.*?>.*?<\/pre>)/s', $content, -1, PREG_SPLIT_DELIM_CAPTURE);
            $result = '';
            
            foreach ($parts as $part) {
                if (strpos($part, '<pre') === 0) {
                    // Don't process directives inside <pre> tags
                    $result .= $part;
                } else {
                    // Process directives normally
                    $result .= preg_replace_callback(
                        "/{$escapedPattern}(?:\s*\((.*?)\))?(\r?\n|$)/s",
                        function ($matches) use ($handler) {
                            $expression = isset($matches[1]) ? trim($matches[1]) : '';
                            return $handler($expression) . ($matches[2] ?? '');
                        },
                        $part
                    );
                }
            }
            
            return $result;
        }
        
        // Otherwise, compile it as a directive
        return preg_replace_callback(
            "/{$escapedPattern}(?:\s*\((.*?)\))?(\r?\n|$)/s",
            function ($matches) use ($handler) {
                $expression = isset($matches[1]) ? trim($matches[1]) : '';
                return $handler($expression) . ($matches[2] ?? '');
            },
            $content
        );
    }
}