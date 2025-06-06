<?php

/**
 * Pluma Framework - Entry Point
 */

// Load the autoloader
require_once __DIR__ . '/../bootstrap/autoload.php';

// Load environment variables if .env file exists
if (file_exists(PLUMA_ROOT . '/.env')) {
    $dotenv = new \Dotenv\Dotenv(PLUMA_ROOT);
    $dotenv->load();
}

// Create and run the application
$app = new \Pluma\Core\Application();
$app->run();