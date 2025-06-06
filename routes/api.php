<?php

use Pluma\Core\ExampleController;
use Pluma\Http\Route;
use Pluma\Http\Router;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// API example
Route::get('/api', [ExampleController::class, 'api']);

// Example API routes
Route::get('/users', function () {
    return [
        'users' => [
            ['id' => 1, 'name' => 'John Doe'],
            ['id' => 2, 'name' => 'Jane Smith'],
            ['id' => 3, 'name' => 'Bob Johnson'],
        ]
    ];
});

Route::post('/users', function () {
    // Create a new user
    return ['message' => 'User created successfully'];
});

Route::get('/users/{id}', function ($id) {
    // Get a specific user
    return ['user' => ['id' => $id, 'name' => 'User ' . $id]];
});

Route::put('/users/{id}', function ($id) {
    // Update a specific user
    return ['message' => 'User ' . $id . ' updated successfully'];
});

Route::delete('/users/{id}', function ($id) {
    // Delete a specific user
    return ['message' => 'User ' . $id . ' deleted successfully'];
});