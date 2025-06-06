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
            'features' => [
                [
                    'title' => 'Petal Templates',
                    'description' => 'A powerful templating engine with inheritance, directives, and more.'
                ],
                [
                    'title' => 'Database Drivers',
                    'description' => 'Support for MySQL, PostgreSQL, SQLite, and MongoDB.'
                ],
                [
                    'title' => 'Modern PHP',
                    'description' => 'Built with PHP 8.2+ features like attributes, enums, and more.'
                ],
                [
                    'title' => 'Dependency Injection',
                    'description' => 'A powerful container for managing your application dependencies.'
                ],
                [
                    'title' => 'Routing',
                    'description' => 'Simple and flexible routing with support for RESTful resources.'
                ],
                [
                    'title' => 'MVC Architecture',
                    'description' => 'Organize your code with Models, Views, and Controllers.'
                ]
            ],
            'showButton' => true
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
            'showExample' => true,
            'team' => [
                [
                    'name' => 'John Doe',
                    'role' => 'Lead Developer',
                    'bio' => 'John has been developing PHP applications for over 10 years.'
                ],
                [
                    'name' => 'Jane Smith',
                    'role' => 'Designer',
                    'bio' => 'Jane is a UI/UX designer with a passion for creating beautiful interfaces.'
                ],
                [
                    'name' => 'Bob Johnson',
                    'role' => 'DevOps Engineer',
                    'bio' => 'Bob ensures that Pluma runs smoothly in production environments.'
                ]
            ]
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
            'user' => [
                'id' => $id,
                'name' => 'User ' . $id,
                'email' => 'user' . $id . '@example.com',
                'created_at' => date('Y-m-d H:i:s')
            ]
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