<?php

/**
 * Pluma Framework - Development Server
 */

// Parse the URI
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// This file allows us to emulate Apache's "mod_rewrite" functionality
if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri)) {
    return false;
}

// Include the front controller
require_once __DIR__ . '/public/index.php';