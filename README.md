# Pluma Framework

Pluma é um framework PHP leve e moderno que utiliza sistema de namespaces para organização do código, aproveitando os recursos mais recentes do PHP 8.2+.

## Características

- Sistema de roteamento simples e poderoso
- Arquitetura MVC
- Injeção de dependências
- ORM básico para interação com banco de dados
- Sistema de templates Petal
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

## CLI

O Pluma Framework inclui uma interface de linha de comando (CLI) para ajudar no desenvolvimento:

```bash
./pluma [comando] [opções] [argumentos]
```

### Comandos Disponíveis

- `serve`: Inicia o servidor de desenvolvimento
- `make:controller`: Cria um novo controller
- `make:model`: Cria um novo model
- `make:command`: Cria um novo comando de console

### Exemplos

Iniciar o servidor de desenvolvimento:

```bash
./pluma serve
```

Especificar host e porta:

```bash
./pluma serve --host=0.0.0.0 --port=8080
```

Criar um controller:

```bash
./pluma make:controller UserController
```

Criar um controller de recurso:

```bash
./pluma make:controller UserController --resource
```

Criar um model:

```bash
./pluma make:model User
```

Criar um model com controller e migration:

```bash
./pluma make:model User --all
```

Criar um comando personalizado:

```bash
./pluma make:command SendEmails
```

## Estrutura de Diretórios

```
pluma/
├── bootstrap/         # Scripts de inicialização
├── config/            # Arquivos de configuração
├── public/            # Diretório público (ponto de entrada)
├── resources/         # Recursos da aplicação
│   └── views/         # Templates de visualização
├── routes/            # Arquivos de rotas
│   ├── web.php        # Rotas para interface web
│   └── api.php        # Rotas para API
├── src/               # Código fonte do framework
│   ├── Container/     # Sistema de injeção de dependências
│   ├── Core/          # Componentes principais
│   ├── Database/      # Camada de acesso a dados
│   ├── Http/          # Componentes HTTP
│   ├── Providers/     # Provedores de serviços
│   └── View/          # Sistema de templates
├── storage/           # Armazenamento de arquivos
│   └── framework/     # Arquivos gerados pelo framework
│       └── views/     # Templates compilados
├── tests/             # Testes automatizados
├── vendor/            # Dependências (gerenciadas pelo Composer)
├── .env.example       # Exemplo de variáveis de ambiente
├── composer.json      # Configuração do Composer
└── README.md          # Documentação
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

As rotas são definidas nos arquivos da pasta `routes`:

### Web Routes (routes/web.php)

```php
<?php

use App\Controllers\HomeController;
use App\Controllers\UserController;

// Rota para a página inicial
Route::get('/', [HomeController::class, 'index']);

// Rota com parâmetros
Route::get('/users/{id}', [UserController::class, 'show']);

// Rota com closure
Route::get('/hello/{name}', function ($name) {
    return "Hello, {$name}!";
});

// Grupo de rotas
Route::group('/admin', function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/users', [AdminController::class, 'users']);
});

// Rotas de recurso (7 rotas)
Route::resource('posts', PostController::class);
```

### API Routes (routes/api.php)

```php
<?php

use App\Controllers\Api\UserController;
use App\Controllers\Api\PostController;

// Rotas de API
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);

// Rotas de recurso para API (5 rotas)
Route::apiResource('posts', PostController::class);

// Rota com retorno JSON
Route::get('/stats', function () {
    return [
        'users_count' => 100,
        'posts_count' => 500,
    ];
});
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

## Trabalhando com Modelos

```php
<?php

namespace App\Models;

use Pluma\Database\Model;

class User extends Model
{
    // Define a tabela (opcional, por padrão é o nome da classe no plural)
    protected static string $table = 'users';
    
    // Define os campos que podem ser preenchidos em massa
    protected array $fillable = ['name', 'email', 'password'];
    
    // Define os campos que não podem ser preenchidos em massa
    protected array $guarded = ['id', 'created_at', 'updated_at'];
    
    // Exemplo de método personalizado
    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = password_hash($value, PASSWORD_BCRYPT);
    }
}
```

## Usando o Container de Injeção de Dependências

```php
<?php

// Registrar um serviço
$container = $app->getContainer();
$container->singleton(UserRepository::class, function ($container) {
    return new UserRepository($container->get(Database::class));
});

// Resolver um serviço
$userRepository = $container->get(UserRepository::class);
```

## Configuração do Banco de Dados

O Pluma Framework suporta múltiplos drivers de banco de dados através de um sistema de drivers flexível. Os drivers suportados nativamente são:

- MySQL
- SQLite
- PostgreSQL
- MongoDB (requer extensão MongoDB para PHP)

Edite o arquivo `.env` na raiz do projeto para configurar o banco de dados:

```
# Escolha o driver (mysql, sqlite, pgsql, mongodb)
DB_CONNECTION=mysql

# MySQL
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=pluma
DB_USERNAME=root
DB_PASSWORD=secret
DB_PREFIX=

# SQLite
# DB_CONNECTION=sqlite
# DB_DATABASE=/absolute/path/to/database.sqlite

# PostgreSQL
# DB_CONNECTION=pgsql
# DB_HOST=localhost
# DB_PORT=5432
# DB_DATABASE=pluma
# DB_USERNAME=postgres
# DB_PASSWORD=secret
# DB_SCHEMA=public

# MongoDB
# DB_CONNECTION=mongodb
# DB_HOST=localhost
# DB_PORT=27017
# DB_DATABASE=pluma
# DB_USERNAME=mongouser
# DB_PASSWORD=secret
# DB_AUTH_SOURCE=admin
```

### Registrando Drivers Personalizados

Você pode registrar drivers personalizados para outros bancos de dados:

```php
<?php

use Pluma\Database\Drivers\DatabaseDriverFactory;

// Registrar um driver personalizado
DatabaseDriverFactory::register('mssql', \App\Database\Drivers\MsSqlDriver::class);

// Usar o driver
$db = new \Pluma\Database\Database([
    'driver' => 'mssql',
    'host' => 'localhost',
    'port' => 1433,
    'database' => 'pluma',
    'username' => 'sa',
    'password' => 'secret',
]);
```

## Sistema de Templates Petal

O Pluma Framework inclui um poderoso sistema de templates chamado Petal, que permite escrever templates HTML com sintaxe elegante e recursos avançados:

### Características do Petal

- Herança de templates com `@extends` e `@section`
- Saída com escape automático: `{{ $variable }}`
- Saída sem escape: `{!! $variable !!}`
- Comentários: `{# Este é um comentário #}`
- Estruturas de controle: `@if`, `@foreach`, `@for`, etc.
- Inclusão de sub-templates: `@include('template')`
- Diretivas personalizadas

### Exemplo de Template Petal

```php
@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h1>{{ $title }}</h1>
        </div>
        <div class="card-body">
            <p>{{ $message }}</p>
            
            @if($showButton)
                <a href="/docs" class="btn btn-primary">Documentação</a>
            @endif
            
            <ul>
                @foreach($items as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
```

### Usando o Petal em Controllers

```php
public function index(): string
{
    return $this->view('welcome', [
        'title' => 'Bem-vindo ao Pluma',
        'message' => 'Um framework PHP moderno',
        'showButton' => true,
        'items' => ['Item 1', 'Item 2', 'Item 3']
    ]);
}
```

### Página de Demonstração

O Pluma inclui uma página de demonstração completa do sistema Petal que você pode acessar em `/petal-demo`. Esta página mostra todos os recursos do Petal em ação.

Para mais detalhes, consulte a [documentação completa do Petal](docs/petal-templates.md).

## Contribuindo

Contribuições são bem-vindas! Sinta-se à vontade para abrir issues ou enviar pull requests.

## Licença

MIT