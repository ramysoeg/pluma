@extends('layouts.example')

@section('content')
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Sistema de Templates Petal</h2>
                </div>
                <div class="card-body">
                    <p class="lead">O Petal é um sistema de templates poderoso e elegante para o Pluma Framework.</p>
                    <p>Ele permite que você escreva HTML com uma sintaxe expressiva e recursos avançados como herança de templates, diretivas, estruturas de controle e muito mais.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3>Sintaxe Básica</h3>
                </div>
                <div class="card-body">
                    <h4>Exibindo Variáveis</h4>
                    <p>Use a sintaxe <code>{{ '{{ $variable }}' }}</code> para exibir variáveis com escape HTML:</p>
                    <pre><code class="language-php">{{ '<h1>{{ $title }}</h1>
<p>{{ $message }}</p>' }}</code></pre>
                    
                    <h4>Exibindo HTML</h4>
                    <p>Use a sintaxe <code>{!! '{!! $variable !!}' !!}</code> para exibir HTML sem escape:</p>
                    <pre><code class="language-php">{!! '<div>{!! $htmlContent !!}</div>' !!}</code></pre>
                    
                    <h4>Comentários</h4>
                    <p>Use a sintaxe <code>{# 'Comentário' #}</code> para adicionar comentários que não serão renderizados:</p>
                    <pre><code class="language-php">{# 'Este é um comentário que não será renderizado' #}
<h1>{{ $title }}</h1></code></pre>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3>Estruturas de Controle</h3>
                </div>
                <div class="card-body">
                    <h4>Condicionais</h4>
                    <pre><code class="language-php">@php
@if($user->isAdmin)
    <div class="alert alert-success">
        Bem-vindo, administrador!
    </div>
@elseif($user->isEditor)
    <div class="alert alert-info">
        Bem-vindo, editor!
    </div>
@else
    <div class="alert alert-primary">
        Bem-vindo, usuário!
    </div>
@endif
@endphp</code></pre>
                    
                    <h4>Loops</h4>
                    <pre><code class="language-php">@php
<ul>
    @foreach($items as $item)
        <li>{{ $item }}</li>
    @endforeach
</ul>

<div>
    @for($i = 1; $i <= 5; $i++)
        <span>{{ $i }}</span>
    @endfor
</div>

@while($condition)
    <p>Executando enquanto a condição for verdadeira</p>
@endwhile
@endphp</code></pre>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h3>Herança de Templates</h3>
                </div>
                <div class="card-body">
                    <p>A herança de templates permite que você crie um layout base e estenda-o em outras páginas.</p>
                    
                    <h4>Layout Base (layouts/app.petal.php)</h4>
                    <pre><code class="language-php">@php
<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'Meu Site' }}</title>
    <link rel="stylesheet" href="/css/app.css">
    @yield('styles')
</head>
<body>
    <header>
        <nav>
            <!-- Navegação -->
        </nav>
    </header>
    
    <main>
        @yield('content')
    </main>
    
    <footer>
        &copy; {{ date('Y') }} Meu Site
    </footer>
    
    <script src="/js/app.js"></script>
    @yield('scripts')
</body>
</html>
@endphp</code></pre>
                    
                    <h4>Página que Estende o Layout (welcome.petal.php)</h4>
                    <pre><code class="language-php">@php
@extends('layouts.app')

@section('styles')
<style>
    .welcome { color: blue; }
</style>
@endsection

@section('content')
    <div class="welcome">
        <h1>{{ $title }}</h1>
        <p>{{ $message }}</p>
    </div>
@endsection

@section('scripts')
<script>
    console.log('Página carregada!');
</script>
@endsection
@endphp</code></pre>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3>Inclusão de Templates</h3>
                </div>
                <div class="card-body">
                    <p>Você pode incluir outros templates usando a diretiva <code>@include</code>:</p>
                    <pre><code class="language-php">@php
<div class="container">
    <h1>Página Principal</h1>
    
    @include('partials.header')
    
    <div class="content">
        <!-- Conteúdo da página -->
    </div>
    
    @include('partials.footer')
</div>
@endphp</code></pre>
                    
                    <p>Você também pode passar variáveis para o template incluído:</p>
                    <pre><code class="language-php">@php
@include('partials.user-card', [
    'user' => $user,
    'showActions' => true
])
@endphp</code></pre>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3>Diretivas Personalizadas</h3>
                </div>
                <div class="card-body">
                    <p>Você pode registrar suas próprias diretivas personalizadas:</p>
                    <pre><code class="language-php">@php
// Em um service provider ou bootstrap
$view = $app->getContainer()->get(View::class);
$view->directive('datetime', function ($expression) {
    return "<?php echo date('d/m/Y H:i', strtotime($expression)); ?>";
});
@endphp</code></pre>
                    
                    <p>E então usá-las em seus templates:</p>
                    <pre><code class="language-php">@php
<p>Data de publicação: @datetime($post->created_at)</p>
@endphp</code></pre>
                    
                    <p>Isso renderizará algo como:</p>
                    <pre><code class="language-html"><p>Data de publicação: 15/06/2025 14:30</p></code></pre>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Usando o Petal em Controllers</h3>
                </div>
                <div class="card-body">
                    <p>Para renderizar um template Petal em um controller, use o método <code>view()</code>:</p>
                    <pre><code class="language-php">@php
namespace App\Controllers;

use Pluma\Http\Controller;

class HomeController extends Controller
{
    public function index(): string
    {
        return $this->view('home', [
            'title' => 'Página Inicial',
            'message' => 'Bem-vindo ao meu site!',
            'features' => [
                'Feature 1',
                'Feature 2',
                'Feature 3'
            ],
            'showButton' => true
        ]);
    }
}
@endphp</code></pre>
                    
                    <p>O método <code>view()</code> aceita dois parâmetros:</p>
                    <ol>
                        <li>O nome do template (pode usar notação de ponto para subdiretórios, ex: <code>'admin.dashboard'</code>)</li>
                        <li>Um array de dados para passar para o template</li>
                    </ol>
                    
                    <p>Os arquivos de template Petal devem ter a extensão <code>.petal.php</code> e estar localizados no diretório <code>resources/views</code>.</p>
                </div>
            </div>
        </div>
    </div>
@endsection