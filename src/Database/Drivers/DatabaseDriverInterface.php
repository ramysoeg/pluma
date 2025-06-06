<?php

namespace Pluma\Database\Drivers;

/**
 * Database Driver Interface
 */
interface DatabaseDriverInterface
{
    /**
     * Connect to the database
     */
    public function connect(): void;
    
    /**
     * Disconnect from the database
     */
    public function disconnect(): void;
    
    /**
     * Execute a query
     */
    public function query(string $query, array $params = []): mixed;
    
    /**
     * Execute a query and fetch all results
     */
    public function fetchAll(string $query, array $params = []): array;
    
    /**
     * Execute a query and fetch the first result
     */
    public function fetch(string $query, array $params = []): ?array;
    
    /**
     * Execute a query and fetch the first column of the first result
     */
    public function fetchColumn(string $query, array $params = []): mixed;
    
    /**
     * Execute a query and return the number of affected rows
     */
    public function execute(string $query, array $params = []): int;
    
    /**
     * Begin a transaction
     */
    public function beginTransaction(): bool;
    
    /**
     * Commit a transaction
     */
    public function commit(): bool;
    
    /**
     * Rollback a transaction
     */
    public function rollback(): bool;
    
    /**
     * Get the last inserted ID
     */
    public function lastInsertId(?string $name = null): string;
    
    /**
     * Get the database connection
     */
    public function getConnection(): mixed;
    
    /**
     * Check if the driver is connected
     */
    public function isConnected(): bool;
    
    /**
     * Get the driver name
     */
    public function getName(): string;
}