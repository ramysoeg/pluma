<?php

namespace Pluma\Database\Drivers;

/**
 * Database Driver Factory
 */
class DatabaseDriverFactory
{
    /**
     * The registered drivers
     */
    protected static array $drivers = [
        'mysql' => MySqlDriver::class,
        'sqlite' => SqliteDriver::class,
        'pgsql' => PostgreSqlDriver::class,
    ];
    
    /**
     * Create a database driver
     */
    public static function create(string $driver, array $config): DatabaseDriverInterface
    {
        if (!isset(self::$drivers[$driver])) {
            throw new \InvalidArgumentException("Driver {$driver} not found");
        }
        
        $driverClass = self::$drivers[$driver];
        
        return new $driverClass($config);
    }
    
    /**
     * Register a database driver
     */
    public static function register(string $name, string $driverClass): void
    {
        if (!class_exists($driverClass)) {
            throw new \InvalidArgumentException("Driver class {$driverClass} not found");
        }
        
        if (!is_subclass_of($driverClass, DatabaseDriverInterface::class)) {
            throw new \InvalidArgumentException("Driver class {$driverClass} must implement DatabaseDriverInterface");
        }
        
        self::$drivers[$name] = $driverClass;
    }
    
    /**
     * Get all registered drivers
     */
    public static function getDrivers(): array
    {
        return self::$drivers;
    }
    
    /**
     * Check if a driver is registered
     */
    public static function hasDriver(string $name): bool
    {
        return isset(self::$drivers[$name]);
    }
}