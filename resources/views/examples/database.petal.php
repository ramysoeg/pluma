@extends('layouts.example')

@section('content')
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Banco de Dados com Pluma</h2>
                </div>
                <div class="card-body">
                    <p class="lead">O Pluma Framework oferece um sistema flexível de drivers de banco de dados para trabalhar com diferentes SGBDs.</p>
                    <p>Nesta página, você encontrará exemplos de como usar o sistema de banco de dados do Pluma com diferentes drivers.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3>Configuração do Banco de Dados</h3>
                </div>
                <div class="card-body">
                    <p>A configuração do banco de dados é feita no arquivo <code>.env</code> na raiz do projeto:</p>
                    <pre><code class="language-ini"># Escolha o driver (mysql, sqlite, pgsql, mongodb)
DB_CONNECTION=mysql

# MySQL
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=pluma
DB_USERNAME=root
DB_PASSWORD=secret
DB_PREFIX=</code></pre>
                    
                    <p>Você também pode configurar outros drivers:</p>
                    <pre><code class="language-ini"># SQLite
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
# DB_AUTH_SOURCE=admin</code></pre>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3>Conexão com o Banco de Dados</h3>
                </div>
                <div class="card-body">
                    <p>Para se conectar ao banco de dados, você pode usar a classe <code>Database</code>:</p>
                    <pre><code class="language-php">@php
use Pluma\Database\Database;

// Usando a configuração padrão do .env
$db = new Database(config('database.connections.' . config('database.default')));

// Ou especificando a configuração manualmente
$db = new Database([
    'driver' => 'mysql',
    'host' => 'localhost',
    'port' => 3306,
    'database' => 'pluma',
    'username' => 'root',
    'password' => 'secret',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
]);
@endphp</code></pre>
                    
                    <p>Você também pode registrar drivers personalizados:</p>
                    <pre><code class="language-php">@php
use Pluma\Database\Drivers\DatabaseDriverFactory;

// Registrar um driver personalizado
DatabaseDriverFactory::register('mssql', \App\Database\Drivers\MsSqlDriver::class);

// Usar o driver
$db = new Database([
    'driver' => 'mssql',
    'host' => 'localhost',
    'port' => 1433,
    'database' => 'pluma',
    'username' => 'sa',
    'password' => 'secret',
]);
@endphp</code></pre>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h3>Consultas SQL</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Consultas Básicas</h4>
                            <pre><code class="language-php">@php
// Consulta simples
$users = $db->fetchAll("SELECT * FROM users");

// Consulta com parâmetros
$user = $db->fetch("SELECT * FROM users WHERE id = ?", [1]);

// Consulta com parâmetros nomeados
$posts = $db->fetchAll(
    "SELECT * FROM posts WHERE user_id = :user_id AND status = :status",
    ['user_id' => 1, 'status' => 'published']
);

// Obter um único valor
$count = $db->fetchColumn("SELECT COUNT(*) FROM users");

// Executar uma consulta sem retorno
$db->execute("UPDATE users SET status = ? WHERE id = ?", ['active', 1]);
@endphp</code></pre>
                        </div>
                        
                        <div class="col-md-6">
                            <h4>Transações</h4>
                            <pre><code class="language-php">@php
try {
    // Iniciar transação
    $db->beginTransaction();
    
    // Executar consultas
    $db->execute("INSERT INTO users (name, email) VALUES (?, ?)", 
        ['John Doe', 'john@example.com']);
    
    $userId = $db->lastInsertId();
    
    $db->execute("INSERT INTO profiles (user_id, bio) VALUES (?, ?)", 
        [$userId, 'Lorem ipsum dolor sit amet']);
    
    // Confirmar transação
    $db->commit();
} catch (\Exception $e) {
    // Reverter transação em caso de erro
    $db->rollback();
    throw $e;
}
@endphp</code></pre>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h4>Exemplo Completo: Lista de Usuários</h4>
                        <pre><code class="language-php">@php
namespace App\Controllers;

use Pluma\Http\Controller;
use Pluma\Database\Database;

class UserController extends Controller
{
    protected Database $db;
    
    public function __construct(Database $db)
    {
        $this->db = $db;
    }
    
    public function index(): string
    {
        $users = $this->db->fetchAll("SELECT * FROM users ORDER BY created_at DESC");
        
        return $this->view('users.index', [
            'users' => $users
        ]);
    }
    
    public function show(int $id): string
    {
        $user = $this->db->fetch("SELECT * FROM users WHERE id = ?", [$id]);
        
        if (!$user) {
            return $this->view('errors.404', [
                'message' => 'Usuário não encontrado'
            ]);
        }
        
        $posts = $this->db->fetchAll(
            "SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC",
            [$id]
        );
        
        return $this->view('users.show', [
            'user' => $user,
            'posts' => $posts
        ]);
    }
}
@endphp</code></pre>
                        
                        <p>E o template correspondente:</p>
                        <pre><code class="language-php">@php
@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h1>Lista de Usuários</h1>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Data de Criação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user['id'] }}</td>
                            <td>{{ $user['name'] }}</td>
                            <td>{{ $user['email'] }}</td>
                            <td>{{ $user['created_at'] }}</td>
                            <td>
                                <a href="/users/{{ $user['id'] }}" class="btn btn-sm btn-info">Ver</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@endphp</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Drivers Suportados</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($drivers as $driver)
                            <div class="col-md-3 mb-4">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <div class="feature-icon mx-auto">
                                            <i class="fas {{ $driver['icon'] }}"></i>
                                        </div>
                                        <h4>{{ $driver['name'] }}</h4>
                                        <p>{{ $driver['description'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection