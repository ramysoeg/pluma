@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h2>{{ $title }}</h2>
            </div>
            <div class="card-body">
                <p class="lead">{{ $description }}</p>
                
                <h3>O que é o Petal?</h3>
                <p>Petal é o sistema de templates do Pluma Framework, inspirado em outros sistemas modernos como Blade e Twig, mas otimizado para PHP 8.2+.</p>
                
                <div class="alert alert-info">
                    <strong>Dica:</strong> Você pode usar o Petal para criar templates reutilizáveis e manter seu código organizado.
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Recursos do Petal</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($features as $feature)
                                <li class="list-group-item">
                                    <strong>{{ $feature['name'] }}</strong>: {{ $feature['description'] }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Exemplos de Sintaxe</h3>
                    </div>
                    <div class="card-body">
                        <h4>Estruturas de Controle</h4>
                        
                        <h5>Condicionais</h5>
                        <pre><code>@if($condition)
    // código aqui
@elseif($anotherCondition)
    // outro código
@else
    // código padrão
@endif</code></pre>

                        <h5>Loops</h5>
                        <pre><code>@foreach($items as $item)
    {{ $item }}
@endforeach

@for($i = 0; $i < 10; $i++)
    {{ $i }}
@endfor

@while($condition)
    // código aqui
@endwhile</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h3>Demonstração de Condicionais</h3>
            </div>
            <div class="card-body">
                @if($showDemo)
                    <div class="alert alert-success">
                        <p>Esta seção está sendo exibida porque a variável <code>$showDemo</code> é verdadeira.</p>
                        
                        @if($user['isAdmin'])
                            <p>Você está vendo conteúdo de administrador porque <code>$user['isAdmin']</code> é verdadeiro.</p>
                        @else
                            <p>Você está vendo conteúdo de usuário comum porque <code>$user['isAdmin']</code> é falso.</p>
                        @endif
                    </div>
                @else
                    <div class="alert alert-warning">
                        Esta seção não deveria estar visível porque <code>$showDemo</code> é falso.
                    </div>
                @endif
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h3>Demonstração de Loops</h3>
            </div>
            <div class="card-body">
                <h4>Lista de Usuários</h4>
                <div class="row">
                    @foreach($users as $user)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-header {{ $user['isAdmin'] ? 'bg-danger text-white' : 'bg-info text-white' }}">
                                    {{ $user['name'] }}
                                </div>
                                <div class="card-body">
                                    <p><strong>Email:</strong> {{ $user['email'] }}</p>
                                    <p><strong>Função:</strong> {{ $user['isAdmin'] ? 'Administrador' : 'Usuário' }}</p>
                                </div>
                                <div class="card-footer">
                                    <a href="/users/{{ $user['id'] }}" class="btn btn-sm btn-primary">Ver Perfil</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h3>Código PHP Embutido</h3>
            </div>
            <div class="card-body">
                <p>Você pode incluir código PHP diretamente usando a diretiva <code>@php</code>:</p>
                
                <pre><code>@php
    $sum = 0;
    for ($i = 1; $i <= 10; $i++) {
        $sum += $i;
    }
    echo "A soma dos números de 1 a 10 é: " . $sum;
@endphp</code></pre>

                <div class="alert alert-secondary">
                    Resultado:
                    <div class="mt-2">
                        @php
                            $sum = 0;
                            for ($i = 1; $i <= 10; $i++) {
                                $sum += $i;
                            }
                            echo "A soma dos números de 1 a 10 é: " . $sum;
                        @endphp
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h3>Comentários</h3>
            </div>
            <div class="card-body">
                <p>Você pode adicionar comentários que não serão renderizados no HTML final:</p>
                
                <pre><code>{# Este comentário não aparecerá no HTML final #}</code></pre>

                {# Este é um comentário que não aparecerá no HTML final #}
                
                <p>Comentários HTML normais também funcionam:</p>
                
                <pre><code>&lt;!-- Este é um comentário HTML normal --&gt;</code></pre>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    pre {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 0.25rem;
        border: 1px solid #dee2e6;
    }
    
    code {
        color: #e83e8c;
    }
    
    .card-header h3 {
        margin-bottom: 0;
    }
</style>
@endsection