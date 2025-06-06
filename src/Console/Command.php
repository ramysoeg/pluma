<?php

namespace Pluma\Console;

/**
 * Base Command Class
 */
abstract class Command
{
    /**
     * The command name
     */
    protected string $name = '';
    
    /**
     * The command description
     */
    protected string $description = '';
    
    /**
     * The command arguments
     */
    protected array $arguments = [];
    
    /**
     * The command options
     */
    protected array $options = [];
    
    /**
     * Get the command name
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * Get the command description
     */
    public function getDescription(): string
    {
        return $this->description;
    }
    
    /**
     * Get the command arguments
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
    
    /**
     * Get the command options
     */
    public function getOptions(): array
    {
        return $this->options;
    }
    
    /**
     * Execute the command
     */
    abstract public function execute(array $args = [], array $options = []): int;
    
    /**
     * Display a message
     */
    protected function line(string $message): void
    {
        echo $message . PHP_EOL;
    }
    
    /**
     * Display an info message
     */
    protected function info(string $message): void
    {
        echo "\033[32m" . $message . "\033[0m" . PHP_EOL;
    }
    
    /**
     * Display an error message
     */
    protected function error(string $message): void
    {
        echo "\033[31m" . $message . "\033[0m" . PHP_EOL;
    }
    
    /**
     * Display a warning message
     */
    protected function warning(string $message): void
    {
        echo "\033[33m" . $message . "\033[0m" . PHP_EOL;
    }
    
    /**
     * Display a success message
     */
    protected function success(string $message): void
    {
        echo "\033[32m" . $message . "\033[0m" . PHP_EOL;
    }
}