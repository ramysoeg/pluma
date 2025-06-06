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
     * 
     * @return string The rendered view
     */
    public function index(): string
    {
        return $this->view('welcome', [
            'title' => 'Welcome to Pluma Framework',
            'message' => 'A lightweight PHP framework with namespace support',
        ]);
    }
    
    /**
     * Show the about page
     * 
     * @return string The rendered view
     */
    public function about(): string
    {
        return $this->view('about', [
            'title' => 'About Pluma Framework',
            'message' => 'Pluma is a lightweight PHP framework with namespace support',
        ]);
    }
    
    /**
     * Show user profile
     * 
     * @param int $id The user ID
     * @return string The rendered view
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
     * 
     * @param Request $request The request instance
     * @return void
     */
    public function api(Request $request): void
    {
        $this->json([
            'success' => true,
            'message' => 'API response',
            'data' => [
                'method' => $request->getRequestMethod(),
                'path' => $request->getRequestPath(),
                'query' => $request->getQueryParams(),
            ],
        ]);
    }
}