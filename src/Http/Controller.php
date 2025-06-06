<?php

namespace Pluma\Http;

use Pluma\Container\Container;
use Pluma\View\View;

/**
 * Base Controller Class
 */
abstract class Controller
{
    /**
     * Controller constructor
     */
    public function __construct(
        /**
         * The dependency injection container
         */
        protected readonly Container $container,
        
        /**
         * The view instance
         */
        protected readonly View $view,
        
        /**
         * The request instance
         */
        protected readonly Request $request,
        
        /**
         * The response instance
         */
        protected readonly Response $response
    ) {
        // Constructor promotion handles property assignment
    }
    
    /**
     * Render a view
     */
    protected function view(string $view, array $data = []): string
    {
        return $this->view->render($view, $data);
    }
    
    /**
     * Return a JSON response
     */
    protected function json(mixed $data, int $statusCode = 200): never
    {
        $this->response->json($data, $statusCode);
    }
    
    /**
     * Redirect to a URL
     */
    protected function redirect(string $url, int $statusCode = 302): never
    {
        $this->response->redirect($url, $statusCode);
    }
    
    /**
     * Get the request instance
     */
    protected function request(): Request
    {
        return $this->request;
    }
}