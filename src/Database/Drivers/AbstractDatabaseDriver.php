<?php

namespace Pluma\Database\Drivers;

/**
 * Abstract Database Driver
 */
abstract class AbstractDatabaseDriver implements DatabaseDriverInterface
{
    /**
     * The database configuration
     */
    protected array $config;
    
    /**
     * Whether the driver is connected
     */
    protected bool $connected = false;
    
    /**
     * Constructor
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }
    
    /**
     * Check if the driver is connected
     */
    public function isConnected(): bool
    {
        return $this->connected;
    }
    
    /**
     * Get the database configuration
     */
    public function getConfig(): array
    {
        return $this->config;
    }
    
    /**
     * Get a configuration value
     */
    public function getConfigValue(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }
}