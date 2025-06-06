<?php

namespace Pluma\Database\Drivers;

/**
 * SQLite Database Driver
 */
class SqliteDriver extends AbstractDatabaseDriver
{
    /**
     * The PDO instance
     */
    protected ?\PDO $pdo = null;
    
    /**
     * Connect to the database
     */
    public function connect(): void
    {
        if ($this->isConnected()) {
            return;
        }
        
        $database = $this->getConfigValue('database', ':memory:');
        $options = $this->getConfigValue('options', []);
        
        // Default options
        $defaultOptions = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        // Merge the options
        $options = array_merge($defaultOptions, $options);
        
        // Create the PDO instance
        $this->pdo = new \PDO("sqlite:{$database}", null, null, $options);
        $this->connected = true;
        
        // Enable foreign keys
        $this->pdo->exec('PRAGMA foreign_keys = ON;');
    }
    
    /**
     * Disconnect from the database
     */
    public function disconnect(): void
    {
        $this->pdo = null;
        $this->connected = false;
    }
    
    /**
     * Execute a query
     */
    public function query(string $query, array $params = []): mixed
    {
        if (!$this->isConnected()) {
            $this->connect();
        }
        
        $statement = $this->pdo->prepare($query);
        $statement->execute($params);
        
        return $statement;
    }
    
    /**
     * Execute a query and fetch all results
     */
    public function fetchAll(string $query, array $params = []): array
    {
        return $this->query($query, $params)->fetchAll();
    }
    
    /**
     * Execute a query and fetch the first result
     */
    public function fetch(string $query, array $params = []): ?array
    {
        $result = $this->query($query, $params)->fetch();
        
        return $result !== false ? $result : null;
    }
    
    /**
     * Execute a query and fetch the first column of the first result
     */
    public function fetchColumn(string $query, array $params = []): mixed
    {
        return $this->query($query, $params)->fetchColumn();
    }
    
    /**
     * Execute a query and return the number of affected rows
     */
    public function execute(string $query, array $params = []): int
    {
        return $this->query($query, $params)->rowCount();
    }
    
    /**
     * Begin a transaction
     */
    public function beginTransaction(): bool
    {
        if (!$this->isConnected()) {
            $this->connect();
        }
        
        return $this->pdo->beginTransaction();
    }
    
    /**
     * Commit a transaction
     */
    public function commit(): bool
    {
        if (!$this->isConnected()) {
            return false;
        }
        
        return $this->pdo->commit();
    }
    
    /**
     * Rollback a transaction
     */
    public function rollback(): bool
    {
        if (!$this->isConnected()) {
            return false;
        }
        
        return $this->pdo->rollBack();
    }
    
    /**
     * Get the last inserted ID
     */
    public function lastInsertId(?string $name = null): string
    {
        if (!$this->isConnected()) {
            $this->connect();
        }
        
        return $this->pdo->lastInsertId($name);
    }
    
    /**
     * Get the database connection
     */
    public function getConnection(): mixed
    {
        if (!$this->isConnected()) {
            $this->connect();
        }
        
        return $this->pdo;
    }
    
    /**
     * Get the driver name
     */
    public function getName(): string
    {
        return 'sqlite';
    }
}