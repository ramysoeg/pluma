<?php

namespace Pluma\Console;

/**
 * Console Application
 */
class Application
{
    /**
     * The application name
     */
    protected string $name = 'Pluma Framework';
    
    /**
     * The application version
     */
    protected string $version = '1.0.0';
    
    /**
     * The registered commands
     */
    protected array $commands = [];
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->registerDefaultCommands();
    }
    
    /**
     * Register the default commands
     */
    protected function registerDefaultCommands(): void
    {
        $this->add(new Commands\ServeCommand());
        $this->add(new Commands\MakeControllerCommand());
        $this->add(new Commands\MakeModelCommand());
        $this->add(new Commands\MakeCommandCommand());
    }
    
    /**
     * Add a command
     */
    public function add(Command $command): self
    {
        $this->commands[$command->getName()] = $command;
        
        return $this;
    }
    
    /**
     * Get all commands
     */
    public function getCommands(): array
    {
        return $this->commands;
    }
    
    /**
     * Get a command by name
     */
    public function getCommand(string $name): ?Command
    {
        return $this->commands[$name] ?? null;
    }
    
    /**
     * Run the application
     */
    public function run(array $argv = []): int
    {
        // Remove the script name from the arguments
        array_shift($argv);
        
        // If no command is provided, show the help
        if (empty($argv) || $argv[0] === 'help' || $argv[0] === '--help' || $argv[0] === '-h') {
            return $this->showHelp();
        }
        
        // Get the command name
        $name = $argv[0];
        
        // Remove the command name from the arguments
        array_shift($argv);
        
        // Get the command
        $command = $this->getCommand($name);
        
        // If the command doesn't exist, show an error
        if ($command === null) {
            $this->showError("Command '{$name}' not found.");
            return 1;
        }
        
        // Parse the arguments and options
        [$args, $options] = $this->parseArguments($argv);
        
        // Execute the command
        return $command->execute($args, $options);
    }
    
    /**
     * Parse the arguments and options
     */
    protected function parseArguments(array $argv): array
    {
        $args = [];
        $options = [];
        
        foreach ($argv as $arg) {
            // If the argument starts with --, it's a long option
            if (strpos($arg, '--') === 0) {
                $option = substr($arg, 2);
                
                // If the option contains =, it has a value
                if (strpos($option, '=') !== false) {
                    [$name, $value] = explode('=', $option, 2);
                    $options[$name] = $value;
                } else {
                    $options[$option] = true;
                }
            }
            // If the argument starts with -, it's a short option
            elseif (strpos($arg, '-') === 0) {
                $option = substr($arg, 1);
                
                // If the option contains =, it has a value
                if (strpos($option, '=') !== false) {
                    [$name, $value] = explode('=', $option, 2);
                    $options[$name] = $value;
                } else {
                    $options[$option] = true;
                }
            }
            // Otherwise, it's an argument
            else {
                $args[] = $arg;
            }
        }
        
        return [$args, $options];
    }
    
    /**
     * Show the help
     */
    protected function showHelp(): int
    {
        echo "{$this->name} {$this->version}" . PHP_EOL;
        echo PHP_EOL;
        echo "Usage:" . PHP_EOL;
        echo "  command [options] [arguments]" . PHP_EOL;
        echo PHP_EOL;
        echo "Available commands:" . PHP_EOL;
        
        // Get the commands grouped by namespace
        $commands = $this->getCommands();
        
        // Sort the commands by name
        ksort($commands);
        
        // Display the commands
        foreach ($commands as $name => $command) {
            echo "  {$name}" . str_repeat(' ', max(0, 20 - strlen($name))) . "{$command->getDescription()}" . PHP_EOL;
        }
        
        return 0;
    }
    
    /**
     * Show an error
     */
    protected function showError(string $message): void
    {
        echo "\033[31m{$message}\033[0m" . PHP_EOL;
    }
}