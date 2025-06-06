<?php

namespace Pluma\Database;

use Pluma\Database\Drivers\DatabaseDriverFactory;
use Pluma\Database\Drivers\DatabaseDriverInterface;

/**
 * Database Class
 */
class Database
{
    /**
     * The database driver
     */
    protected DatabaseDriverInterface $driver;
    
    /**
     * The database configuration
     */
    protected array $config;
    
    /**
     * Database constructor
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->connect();
    }
    
    /**
     * Connect to the database
     */
    protected function connect(): void
    {
        $driver = $this->config['driver'] ?? 'mysql';
        
        // Create the driver
        $this->driver = DatabaseDriverFactory::create($driver, $this->config);
        
        // Connect to the database
        $this->driver->connect();
    }
    
    /**
     * Get the database driver
     */
    public function getDriver(): DatabaseDriverInterface
    {
        return $this->driver;
    }
    
    /**
     * Get the database connection
     */
    public function getConnection(): mixed
    {
        return $this->driver->getConnection();
    }
    
    /**
     * Execute a query
     */
    public function query(string $query, array $params = []): mixed
    {
        return $this->driver->query($query, $params);
    }
    
    /**
     * Execute a query and fetch all results
     */
    public function fetchAll(string $query, array $params = []): array
    {
        return $this->driver->fetchAll($query, $params);
    }
    
    /**
     * Execute a query and fetch the first result
     */
    public function fetch(string $query, array $params = []): ?array
    {
        return $this->driver->fetch($query, $params);
    }
    
    /**
     * Execute a query and fetch the first column of the first result
     */
    public function fetchColumn(string $query, array $params = []): mixed
    {
        return $this->driver->fetchColumn($query, $params);
    }
    
    /**
     * Execute a query and return the number of affected rows
     */
    public function execute(string $query, array $params = []): int
    {
        return $this->driver->execute($query, $params);
    }
    
    /**
     * Begin a transaction
     */
    public function beginTransaction(): bool
    {
        return $this->driver->beginTransaction();
    }
    
    /**
     * Commit a transaction
     */
    public function commit(): bool
    {
        return $this->driver->commit();
    }
    
    /**
     * Rollback a transaction
     */
    public function rollback(): bool
    {
        return $this->driver->rollback();
    }
    
    /**
     * Get the last inserted ID
     */
    public function lastInsertId(?string $name = null): string
    {
        return $this->driver->lastInsertId($name);
    }
    
    /**
     * Check if the database is connected
     */
    public function isConnected(): bool
    {
        return $this->driver->isConnected();
    }
    
    /**
     * Disconnect from the database
     */
    public function disconnect(): void
    {
        $this->driver->disconnect();
    }
    
    /**
     * Get the driver name
     */
    public function getDriverName(): string
    {
        return $this->driver->getName();
    }
}