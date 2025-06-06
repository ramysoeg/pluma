# Pluma Framework

Pluma é um framework PHP leve e moderno que utiliza sistema de namespaces para organização do código, aproveitando os recursos mais recentes do PHP 8.2+.

## Características

- Sistema de roteamento simples e poderoso
- Arquitetura MVC
- Injeção de dependências
- ORM básico para interação com banco de dados
- Sistema de templates
- Middleware
- PSR-4 autoloading
- Tipagem estrita
- Propriedades readonly
- Tipos de retorno never
- Tipos de união e interseção
- Atributos de promoção de construtor

## Requisitos

- PHP 8.2 ou superior
- Composer 2.0+

## Instalação

```bash
composer create-project ramysoeg/pluma my-project
cd my-project
```

## Uso Básico

```php
<?php
// public/index.php

require_once __DIR__ . '/../bootstrap/autoload.php';

// Carregar variáveis de ambiente
if (file_exists(PLUMA_ROOT . '/.env')) {
    $dotenv = \Dotenv\Dotenv::createImmutable(PLUMA_ROOT);
    $dotenv->load();
}

// Criar e executar a aplicação
$app = new \Pluma\Core\Application();
$app->run();
```

## Definindo Rotas

```php
<?php
// config/routes.php

use Pluma\Http\Router;

return function (Router $router): void {
    // Rota para a página inicial
    $router->get('/', 'App\\Controllers\\HomeController@index');
    
    // Rota com parâmetros
    $router->get('/users/{id}', 'App\\Controllers\\UserController@show');
    
    // Rota com closure
    $router->get('/hello/{name}', function ($container, $name) {
        $response = $container->get('response');
        $response->send("Hello, {$name}!");
    });
    
    // Grupo de rotas
    $router->group('/api', function (Router $router) {
        $router->get('/users', 'App\\Controllers\\Api\\UserController@index');
        $router->post('/users', 'App\\Controllers\\Api\\UserController@store');
    });
};
```

## Criando um Controller

```php
<?php

namespace App\Controllers;

use Pluma\Http\Controller;
use Pluma\Http\Request;
use Pluma\Http\Response;

class UserController extends Controller
{
    public function index(): string
    {
        $users = User::all();
        
        return $this->view('users.index', ['users' => $users]);
    }
    
    public function show(int $id): string
    {
        $user = User::find($id);
        
        if (!$user) {
            $this->response->setStatusCode(404);
            return $this->view('errors.404');
        }
        
        return $this->view('users.show', ['user' => $user]);
    }
    
    public function store(Request $request): never
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);
        
        $this->response->json([
            'success' => true,
            'data' => $user,
        ]);
    }
}
```

## Licença

MIT