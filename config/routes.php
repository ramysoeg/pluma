<?php

use Pluma\Core\ExampleController;
use Pluma\Core\ExamplePetalController;
use Pluma\Http\Router;

/**
 * Define application routes
 * 
 * @param Router $router The router instance
 * @return void
 */
return function (Router $router): void {
    // Home page
    $router->get('/', 'Pluma\\Core\\ExampleController@index');
    
    // About page
    $router->get('/about', 'Pluma\\Core\\ExampleController@about');
    
    // User profile
    $router->get('/profile/{id}', 'Pluma\\Core\\ExampleController@profile');
    
    // API example
    $router->get('/api', 'Pluma\\Core\\ExampleController@api');
    
    // Closure example
    $router->get('/hello/{name}', function ($container, $name) {
        $response = $container->get('response');
        $response->setHeader('Content-Type', 'text/html');
        $response->send("Hello, {$name}!");
    });
    
    // Petal template demo
    $router->get('/petal-demo', 'Pluma\\Core\\ExamplePetalController@demo');
};