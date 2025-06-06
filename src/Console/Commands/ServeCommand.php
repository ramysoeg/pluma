<?php

namespace Pluma\Console\Commands;

use Pluma\Console\Command;

/**
 * Serve Command
 */
class ServeCommand extends Command
{
    /**
     * The command name
     */
    protected string $name = 'serve';
    
    /**
     * The command description
     */
    protected string $description = 'Start the Pluma development server';
    
    /**
     * The command arguments
     */
    protected array $arguments = [
        'host' => 'The host to serve the application on',
        'port' => 'The port to serve the application on',
    ];
    
    /**
     * The command options
     */
    protected array $options = [
        '--host' => 'The host to serve the application on',
        '--port' => 'The port to serve the application on',
    ];
    
    /**
     * Execute the command
     */
    public function execute(array $args = [], array $options = []): int
    {
        // Get the host and port
        $host = $options['host'] ?? $args[0] ?? '0.0.0.0';
        $port = $options['port'] ?? $args[1] ?? 12000;
        
        // Show a message
        $this->info("Starting Pluma development server at http://{$host}:{$port}");
        $this->line("Press Ctrl+C to stop the server");
        $this->line("");
        
        // Start the server
        passthru("php -S {$host}:{$port} -t public server.php");
        
        return 0;
    }
}