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
     * @var Container The dependency injection container
     */
    protected Container $container;
    
    /**
     * @var View The view instance
     */
    protected View $view;
    
    /**
     * Controller constructor
     * 
     * @param Container $container The dependency injection container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->view = $container->get(View::class);
    }
    
    /**
     * Render a view
     * 
     * @param string $view The view name
     * @param array $data The view data
     * @return string The rendered view
     */
    protected function view(string $view, array $data = []): string
    {
        return $this->view->render($view, $data);
    }
    
    /**
     * Return a JSON response
     * 
     * @param mixed $data The data to encode as JSON
     * @param int $statusCode The HTTP status code
     * @return void
     */
    protected function json($data, int $statusCode = 200): void
    {
        $response = $this->container->get(Response::class);
        $response->json($data, $statusCode);
    }
    
    /**
     * Redirect to a URL
     * 
     * @param string $url The URL to redirect to
     * @param int $statusCode The HTTP status code
     * @return void
     */
    protected function redirect(string $url, int $statusCode = 302): void
    {
        $response = $this->container->get(Response::class);
        $response->redirect($url, $statusCode);
    }
    
    /**
     * Get the request instance
     * 
     * @return Request The request instance
     */
    protected function request(): Request
    {
        return $this->container->get(Request::class);
    }
}