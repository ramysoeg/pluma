<?php

return [
    'default' => $_ENV['DB_CONNECTION'] ?? 'mysql',
    
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => $_ENV['DB_HOST'] ?? 'localhost',
            'port' => $_ENV['DB_PORT'] ?? 3306,
            'database' => $_ENV['DB_DATABASE'] ?? 'pluma',
            'username' => $_ENV['DB_USERNAME'] ?? 'root',
            'password' => $_ENV['DB_PASSWORD'] ?? '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => $_ENV['DB_PREFIX'] ?? '',
            'options' => [
                // Additional PDO options
            ],
        ],
        
        'sqlite' => [
            'driver' => 'sqlite',
            'database' => $_ENV['DB_DATABASE'] ?? PLUMA_ROOT . '/database/database.sqlite',
            'prefix' => $_ENV['DB_PREFIX'] ?? '',
            'foreign_key_constraints' => true,
            'options' => [
                // Additional PDO options
            ],
        ],
        
        'pgsql' => [
            'driver' => 'pgsql',
            'host' => $_ENV['DB_HOST'] ?? 'localhost',
            'port' => $_ENV['DB_PORT'] ?? 5432,
            'database' => $_ENV['DB_DATABASE'] ?? 'pluma',
            'username' => $_ENV['DB_USERNAME'] ?? 'postgres',
            'password' => $_ENV['DB_PASSWORD'] ?? '',
            'charset' => 'utf8',
            'prefix' => $_ENV['DB_PREFIX'] ?? '',
            'schema' => $_ENV['DB_SCHEMA'] ?? 'public',
            'sslmode' => $_ENV['DB_SSLMODE'] ?? 'prefer',
            'options' => [
                // Additional PDO options
            ],
        ],
        
        'mongodb' => [
            'driver' => 'mongodb',
            'host' => $_ENV['DB_HOST'] ?? 'localhost',
            'port' => $_ENV['DB_PORT'] ?? 27017,
            'database' => $_ENV['DB_DATABASE'] ?? 'pluma',
            'username' => $_ENV['DB_USERNAME'] ?? '',
            'password' => $_ENV['DB_PASSWORD'] ?? '',
            'options' => [
                // MongoDB specific options
                'authSource' => $_ENV['DB_AUTH_SOURCE'] ?? 'admin',
            ],
        ],
    ],
];