<?php

/**
 * Pluma Framework - Autoloader
 */

// Define a constant for the application root path
define('PLUMA_ROOT', dirname(__DIR__));

// Check if Composer's autoloader exists
if (file_exists(PLUMA_ROOT . '/vendor/autoload.php')) {
    // Load Composer's autoloader
    require_once PLUMA_ROOT . '/vendor/autoload.php';
} else {
    // If Composer's autoloader doesn't exist, display an error message
    echo 'Composer autoloader not found. Please run "composer install" in the project root directory.';
    exit(1);
}