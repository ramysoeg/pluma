<?php

namespace Pluma\Console\Commands;

use Pluma\Console\Command;

/**
 * Make Command Command
 */
class MakeCommandCommand extends Command
{
    /**
     * The command name
     */
    protected string $name = 'make:command';
    
    /**
     * The command description
     */
    protected string $description = 'Create a new console command';
    
    /**
     * The command arguments
     */
    protected array $arguments = [
        'name' => 'The name of the command',
    ];
    
    /**
     * Execute the command
     */
    public function execute(array $args = [], array $options = []): int
    {
        // Get the command name
        $name = $args[0] ?? null;
        
        // If no name is provided, show an error
        if ($name === null) {
            $this->error('Command name is required.');
            return 1;
        }
        
        // Create the command
        $this->createCommand($name);
        
        return 0;
    }
    
    /**
     * Create a command
     */
    protected function createCommand(string $name): void
    {
        // Get the command namespace and path
        $namespace = 'App\\Console\\Commands';
        $path = PLUMA_ROOT . '/app/Console/Commands';
        
        // Create the directory if it doesn't exist
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        
        // Get the command class name
        $className = $name;
        
        // If the class name doesn't end with Command, add it
        if (!str_ends_with($className, 'Command')) {
            $className .= 'Command';
        }
        
        // Get the command file path
        $filePath = $path . '/' . $className . '.php';
        
        // If the file already exists, show an error
        if (file_exists($filePath)) {
            $this->error("Command {$className} already exists.");
            return;
        }
        
        // Get the command stub
        $stub = $this->getCommandStub($namespace, $className, $this->getCommandName($className));
        
        // Create the command file
        file_put_contents($filePath, $stub);
        
        // Show a success message
        $this->success("Command {$className} created successfully.");
    }
    
    /**
     * Get the command stub
     */
    protected function getCommandStub(string $namespace, string $className, string $commandName): string
    {
        return <<<PHP
<?php

namespace {$namespace};

use Pluma\Console\Command;

/**
 * {$className}
 */
class {$className} extends Command
{
    /**
     * The command name
     */
    protected string \$name = '{$commandName}';
    
    /**
     * The command description
     */
    protected string \$description = 'Command description';
    
    /**
     * The command arguments
     */
    protected array \$arguments = [
        // 'argument' => 'Description',
    ];
    
    /**
     * The command options
     */
    protected array \$options = [
        // '--option' => 'Description',
    ];
    
    /**
     * Execute the command
     */
    public function execute(array \$args = [], array \$options = []): int
    {
        \$this->info('Command executed successfully!');
        
        return 0;
    }
}
PHP;
    }
    
    /**
     * Get the command name from the class name
     */
    protected function getCommandName(string $className): string
    {
        // Remove the Command suffix
        $name = str_replace('Command', '', $className);
        
        // Convert to snake_case
        $name = strtolower(preg_replace('/(?<!^)[A-Z]/', ':$0', $name));
        
        return $name;
    }
}