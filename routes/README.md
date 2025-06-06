# Sistema de Rotas do Pluma Framework

O Pluma Framework oferece um sistema de rotas simples e poderoso, inspirado no Laravel, para facilitar a definição de rotas em sua aplicação.

## Arquivos de Rotas

As rotas são definidas em arquivos na pasta `routes`:

- `web.php`: Rotas para a interface web da aplicação
- `api.php`: Rotas para a API da aplicação

## Definindo Rotas

### Rotas Básicas

```php
// Rota GET
Route::get('/users', [UserController::class, 'index']);

// Rota POST
Route::post('/users', [UserController::class, 'store']);

// Rota PUT
Route::put('/users/{id}', [UserController::class, 'update']);

// Rota PATCH
Route::patch('/users/{id}', [UserController::class, 'update']);

// Rota DELETE
Route::delete('/users/{id}', [UserController::class, 'destroy']);
```

### Rotas com Closures

Você pode definir rotas usando closures (funções anônimas):

```php
Route::get('/hello', function () {
    return 'Hello, World!';
});

Route::get('/hello/{name}', function ($name) {
    return "Hello, {$name}!";
});
```

### Rotas com Múltiplos Métodos HTTP

```php
// Rota para múltiplos métodos
Route::match(['get', 'post'], '/users/profile', [UserController::class, 'profile']);

// Rota para qualquer método HTTP
Route::any('/users/profile', [UserController::class, 'profile']);
```

### Rotas de Recursos

Você pode definir rotas de recursos para controllers RESTful:

```php
// Rotas de recurso completo (7 rotas)
Route::resource('users', UserController::class);

// Rotas de recurso para API (5 rotas)
Route::apiResource('api/users', UserApiController::class);
```

Isso criará as seguintes rotas:

#### Resource Routes

| Método | URI | Ação | Nome da Rota |
|--------|-----|------|-------------|
| GET | /users | index | users.index |
| GET | /users/create | create | users.create |
| POST | /users | store | users.store |
| GET | /users/{id} | show | users.show |
| GET | /users/{id}/edit | edit | users.edit |
| PUT/PATCH | /users/{id} | update | users.update |
| DELETE | /users/{id} | destroy | users.destroy |

#### API Resource Routes

| Método | URI | Ação | Nome da Rota |
|--------|-----|------|-------------|
| GET | /api/users | index | api.users.index |
| POST | /api/users | store | api.users.store |
| GET | /api/users/{id} | show | api.users.show |
| PUT/PATCH | /api/users/{id} | update | api.users.update |
| DELETE | /api/users/{id} | destroy | api.users.destroy |

## Parâmetros de Rota

Você pode definir parâmetros de rota usando chaves `{}`:

```php
Route::get('/users/{id}', [UserController::class, 'show']);

Route::get('/users/{id}/posts/{post_id}', [UserController::class, 'showPost']);
```

## Grupos de Rotas

Você pode agrupar rotas com um prefixo comum:

```php
Route::group('/admin', function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/users', [AdminController::class, 'users']);
    Route::get('/settings', [AdminController::class, 'settings']);
});
```

Isso criará as seguintes rotas:
- `/admin/dashboard`
- `/admin/users`
- `/admin/settings`

## Retornos de Rota

Nas rotas com closures, você pode retornar diferentes tipos de dados:

```php
// Retornar uma string (HTML)
Route::get('/hello', function () {
    return 'Hello, World!';
});

// Retornar um array (JSON)
Route::get('/api/users', function () {
    return [
        'users' => [
            ['id' => 1, 'name' => 'John'],
            ['id' => 2, 'name' => 'Jane'],
        ]
    ];
});
```

## Exemplos

### Exemplo de Rotas Web

```php
// routes/web.php
<?php

use App\Controllers\HomeController;
use App\Controllers\UserController;

// Página inicial
Route::get('/', [HomeController::class, 'index']);

// Páginas de usuário
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/create', [UserController::class, 'create']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::get('/users/{id}/edit', [UserController::class, 'edit']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

// Ou simplesmente:
Route::resource('users', UserController::class);
```

### Exemplo de Rotas API

```php
// routes/api.php
<?php

use App\Controllers\Api\UserController;
use App\Controllers\Api\PostController;

// Rotas de usuário
Route::apiResource('users', UserController::class);

// Rotas de post
Route::apiResource('posts', PostController::class);

// Rota personalizada
Route::get('/stats', function () {
    return [
        'users_count' => 100,
        'posts_count' => 500,
    ];
});
```