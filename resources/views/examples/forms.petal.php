@extends('layouts.example')

@section('content')
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Formulários com Petal</h2>
                </div>
                <div class="card-body">
                    <p class="lead">O Petal facilita a criação e manipulação de formulários em suas aplicações.</p>
                    <p>Nesta página, você encontrará exemplos de como criar diferentes tipos de formulários usando o sistema de templates Petal.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3>Formulário de Contato</h3>
                </div>
                <div class="card-body">
                    <form action="/contact" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="subject" class="form-label">Assunto</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Mensagem</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Enviar Mensagem</button>
                    </form>
                    
                    <div class="mt-4">
                        <h4>Código do Formulário</h4>
                        <pre><code class="language-php">@php
<form action="/contact" method="POST">
    @csrf
    
    <div class="mb-3">
        <label for="name" class="form-label">Nome</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    
    <div class="mb-3">
        <label for="subject" class="form-label">Assunto</label>
        <input type="text" class="form-control" id="subject" name="subject" required>
    </div>
    
    <div class="mb-3">
        <label for="message" class="form-label">Mensagem</label>
        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">Enviar Mensagem</button>
</form>
@endphp</code></pre>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3>Formulário com Validação</h3>
                </div>
                <div class="card-body">
                    <form action="/register" method="POST" class="needs-validation" novalidate>
                        @csrf
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Nome de Usuário</label>
                            <input type="text" class="form-control {{ isset($errors) && isset($errors['username']) ? 'is-invalid' : '' }}" id="username" name="username" required>
                            @if(isset($errors) && isset($errors['username']))
                                <div class="invalid-feedback">
                                    {{ $errors['username'] }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control {{ isset($errors) && isset($errors['email']) ? 'is-invalid' : '' }}" id="email" name="email" required>
                            @if(isset($errors) && isset($errors['email']))
                                <div class="invalid-feedback">
                                    {{ $errors['email'] }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" class="form-control {{ isset($errors) && isset($errors['password']) ? 'is-invalid' : '' }}" id="password" name="password" required>
                            @if(isset($errors) && isset($errors['password']))
                                <div class="invalid-feedback">
                                    {{ $errors['password'] }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input {{ isset($errors) && isset($errors['terms']) ? 'is-invalid' : '' }}" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">Concordo com os termos e condições</label>
                            @if(isset($errors) && isset($errors['terms']))
                                <div class="invalid-feedback">
                                    {{ $errors['terms'] }}
                                </div>
                            @endif
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </form>
                    
                    <div class="mt-4">
                        <h4>Validação no Controller</h4>
                        <pre><code class="language-php">@php
public function register(Request $request): string
{
    $validator = new Validator($request->getPostParams());
    $validator->rule('required', ['username', 'email', 'password', 'terms']);
    $validator->rule('email', 'email');
    $validator->rule('lengthMin', 'password', 8);
    $validator->rule('equals', 'password_confirmation', 'password');
    
    if (!$validator->validate()) {
        return $this->view('examples.forms', [
            'errors' => $validator->errors(),
            'old' => $request->getPostParams()
        ]);
    }
    
    // Processar o registro...
    
    return $this->redirect('/login');
}
@endphp</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h3>Diretivas para Formulários</h3>
                </div>
                <div class="card-body">
                    <p>O Petal inclui algumas diretivas úteis para trabalhar com formulários:</p>
                    
                    <h4>@csrf</h4>
                    <p>Adiciona um campo oculto com um token CSRF para proteger seus formulários contra ataques CSRF:</p>
                    <pre><code class="language-php">@php
<form action="/login" method="POST">
    @csrf
    <!-- Campos do formulário -->
</form>
@endphp</code></pre>
                    
                    <h4>@method</h4>
                    <p>Permite usar métodos HTTP como PUT, PATCH ou DELETE em formulários (que normalmente só suportam GET e POST):</p>
                    <pre><code class="language-php">@php
<form action="/users/{{ $user->id }}" method="POST">
    @csrf
    @method('PUT')
    <!-- Campos do formulário -->
</form>
@endphp</code></pre>
                    
                    <h4>Trabalhando com Valores Antigos</h4>
                    <p>Você pode manter os valores preenchidos pelo usuário após uma validação falhar:</p>
                    <pre><code class="language-php">@php
<input type="text" 
       name="username" 
       value="{{ $old['username'] ?? '' }}" 
       class="form-control {{ isset($errors) && isset($errors['username']) ? 'is-invalid' : '' }}">

@if(isset($errors) && isset($errors['username']))
    <div class="invalid-feedback">
        {{ $errors['username'] }}
    </div>
@endif
@endphp</code></pre>
                    
                    <h4>Criando um Componente de Formulário</h4>
                    <p>Você pode criar componentes reutilizáveis para campos de formulário:</p>
                    <pre><code class="language-php">@php
<!-- Em resources/views/components/input.petal.php -->
<div class="mb-3">
    <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    <input type="{{ $type ?? 'text' }}" 
           class="form-control {{ isset($errors) && isset($errors[$name]) ? 'is-invalid' : '' }}" 
           id="{{ $id }}" 
           name="{{ $name }}" 
           value="{{ $value ?? ($old[$name] ?? '') }}" 
           {{ $required ? 'required' : '' }}>
    
    @if(isset($errors) && isset($errors[$name]))
        <div class="invalid-feedback">
            {{ $errors[$name] }}
        </div>
    @endif
</div>
@endphp</code></pre>
                    
                    <p>E então usá-lo em seus formulários:</p>
                    <pre><code class="language-php">@php
<form action="/login" method="POST">
    @csrf
    
    @include('components.input', [
        'id' => 'email',
        'name' => 'email',
        'label' => 'Email',
        'type' => 'email',
        'required' => true
    ])
    
    @include('components.input', [
        'id' => 'password',
        'name' => 'password',
        'label' => 'Senha',
        'type' => 'password',
        'required' => true
    ])
    
    <button type="submit" class="btn btn-primary">Entrar</button>
</form>
@endphp</code></pre>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Exemplo de validação do lado do cliente
    (function() {
        'use strict';
        
        // Fetch all forms that need validation
        var forms = document.querySelectorAll('.needs-validation');
        
        // Loop over them and prevent submission
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>
@endsection