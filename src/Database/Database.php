<?php

namespace Pluma\Database;

/**
 * Database Class
 */
class Database
{
    /**
     * @var \PDO The PDO instance
     */
    protected \PDO $pdo;
    
    /**
     * @var array The database configuration
     */
    protected array $config;
    
    /**
     * Database constructor
     * 
     * @param array $config The database configuration
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->connect();
    }
    
    /**
     * Connect to the database
     * 
     * @return void
     */
    protected function connect(): void
    {
        $driver = $this->config['driver'] ?? 'mysql';
        $host = $this->config['host'] ?? 'localhost';
        $port = $this->config['port'] ?? 3306;
        $database = $this->config['database'] ?? '';
        $username = $this->config['username'] ?? 'root';
        $password = $this->config['password'] ?? '';
        $charset = $this->config['charset'] ?? 'utf8mb4';
        $options = $this->config['options'] ?? [];
        
        $dsn = "{$driver}:host={$host};port={$port};dbname={$database};charset={$charset}";
        
        // Default options
        $defaultOptions = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        // Merge the options
        $options = array_merge($defaultOptions, $options);
        
        // Create the PDO instance
        $this->pdo = new \PDO($dsn, $username, $password, $options);
    }
    
    /**
     * Get the PDO instance
     * 
     * @return \PDO The PDO instance
     */
    public function getPdo(): \PDO
    {
        return $this->pdo;
    }
    
    /**
     * Execute a query
     * 
     * @param string $query The SQL query
     * @param array $params The query parameters
     * @return \PDOStatement The PDO statement
     */
    public function query(string $query, array $params = []): \PDOStatement
    {
        $statement = $this->pdo->prepare($query);
        $statement->execute($params);
        
        return $statement;
    }
    
    /**
     * Execute a query and fetch all results
     * 
     * @param string $query The SQL query
     * @param array $params The query parameters
     * @return array The query results
     */
    public function fetchAll(string $query, array $params = []): array
    {
        return $this->query($query, $params)->fetchAll();
    }
    
    /**
     * Execute a query and fetch the first result
     * 
     * @param string $query The SQL query
     * @param array $params The query parameters
     * @return array|null The query result
     */
    public function fetch(string $query, array $params = []): ?array
    {
        $result = $this->query($query, $params)->fetch();
        
        return $result !== false ? $result : null;
    }
    
    /**
     * Execute a query and fetch the first column of the first result
     * 
     * @param string $query The SQL query
     * @param array $params The query parameters
     * @return mixed The query result
     */
    public function fetchColumn(string $query, array $params = [])
    {
        return $this->query($query, $params)->fetchColumn();
    }
    
    /**
     * Execute a query and return the number of affected rows
     * 
     * @param string $query The SQL query
     * @param array $params The query parameters
     * @return int The number of affected rows
     */
    public function execute(string $query, array $params = []): int
    {
        return $this->query($query, $params)->rowCount();
    }
    
    /**
     * Begin a transaction
     * 
     * @return bool Whether the transaction was started
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }
    
    /**
     * Commit a transaction
     * 
     * @return bool Whether the transaction was committed
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }
    
    /**
     * Rollback a transaction
     * 
     * @return bool Whether the transaction was rolled back
     */
    public function rollback(): bool
    {
        return $this->pdo->rollBack();
    }
    
    /**
     * Get the last inserted ID
     * 
     * @param string|null $name The name of the sequence object
     * @return string The last inserted ID
     */
    public function lastInsertId(string $name = null): string
    {
        return $this->pdo->lastInsertId($name);
    }
}