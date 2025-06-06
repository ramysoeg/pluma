<?php

use Pluma\Core\ExampleController;
use Pluma\Core\ExamplePetalController;
use Pluma\Core\ExamplesController;
use Pluma\Http\Route;
use Pluma\Http\Router;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

// Home page
Route::get('/', [ExampleController::class, 'index']);

// About page
Route::get('/about', [ExampleController::class, 'about']);

// User profile
Route::get('/profile/{id}', [ExampleController::class, 'profile']);

// Closure example
Route::get('/hello/{name}', function ($name) {
    return "Hello, {$name}!";
});

// Petal template demo
Route::get('/petal-demo', [ExamplePetalController::class, 'demo']);

// Examples
Route::get('/examples', [ExamplesController::class, 'index']);
Route::get('/examples/templates', [ExamplesController::class, 'templates']);
Route::get('/examples/database', [ExamplesController::class, 'database']);
Route::get('/examples/forms', [ExamplesController::class, 'forms']);