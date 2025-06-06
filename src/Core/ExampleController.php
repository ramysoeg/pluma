<?php

namespace Pluma\Core;

use Pluma\Http\Controller;
use Pluma\Http\Request;

/**
 * Example Controller
 */
class ExampleController extends Controller
{
    /**
     * Show the welcome page
     */
    public function index(): string
    {
        return $this->view('welcome', [
            'title' => 'Welcome to Pluma Framework',
            'message' => 'A lightweight PHP framework with namespace support for PHP 8.2+',
        ]);
    }
    
    /**
     * Show the about page
     */
    public function about(): string
    {
        return $this->view('about', [
            'title' => 'About Pluma Framework',
            'message' => 'Pluma is a lightweight PHP framework with namespace support for PHP 8.2+',
        ]);
    }
    
    /**
     * Show user profile
     */
    public function profile(int $id): string
    {
        return $this->view('profile', [
            'title' => 'User Profile',
            'id' => $id,
        ]);
    }
    
    /**
     * API example
     */
    public function api(): never
    {
        $this->json([
            'success' => true,
            'message' => 'API response',
            'data' => [
                'method' => $this->request->getRequestMethod(),
                'path' => $this->request->getRequestPath(),
                'query' => $this->request->getQueryParams(),
                'php_version' => PHP_VERSION,
            ],
            'framework' => [
                'name' => 'Pluma',
                'version' => '1.0.0',
                'requirements' => [
                    'php' => '>=8.2',
                ],
            ],
        ]);
    }
}