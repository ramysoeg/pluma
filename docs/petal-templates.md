# Sistema de Templates Petal

O Petal é o sistema de templates do Pluma Framework, projetado para ser poderoso, flexível e fácil de usar. Ele combina a simplicidade do PHP com recursos avançados de templates, permitindo que você crie interfaces elegantes e reutilizáveis.

## Características Principais

- **Herança de Templates**: Estenda layouts base e substitua seções específicas
- **Diretivas de Controle**: Use estruturas como `@if`, `@foreach`, `@for` e `@while`
- **Escape Automático**: Proteção contra XSS com escape automático de variáveis
- **Inclusão de Sub-templates**: Reutilize componentes com `@include`
- **Comentários**: Adicione comentários que não aparecem no HTML final
- **Código PHP Embutido**: Execute código PHP diretamente com `@php`
- **Diretivas Personalizadas**: Crie suas próprias diretivas para estender a funcionalidade

## Sintaxe Básica

### Exibição de Variáveis

```php
{{ $variavel }}  // Com escape HTML
{!! $variavel !!}  // Sem escape HTML
```

### Comentários

```php
{# Este comentário não aparecerá no HTML final #}
```

### Estruturas de Controle

#### Condicionais

```php
@if($condicao)
    // código aqui
@elseif($outraCondicao)
    // outro código
@else
    // código padrão
@endif
```

#### Loops

```php
@foreach($itens as $item)
    {{ $item }}
@endforeach

@for($i = 0; $i < 10; $i++)
    {{ $i }}
@endfor

@while($condicao)
    // código aqui
@endwhile
```

### Herança de Templates

#### Layout Base (layouts/app.petal.php)

```php
<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'Meu Site' }}</title>
    @yield('styles')
</head>
<body>
    <header>
        @yield('header', '<h1>Cabeçalho Padrão</h1>')
    </header>
    
    <main>
        @yield('content')
    </main>
    
    <footer>
        &copy; {{ date('Y') }} Meu Site
    </footer>
    
    @yield('scripts')
</body>
</html>
```

#### Página que Estende o Layout

```php
@extends('layouts.app')

@section('header')
    <h1>Título Personalizado</h1>
@endsection

@section('content')
    <p>Conteúdo da página</p>
@endsection

@section('styles')
<style>
    body { background-color: #f5f5f5; }
</style>
@endsection
```

### Inclusão de Sub-templates

```php
@include('components.card', ['title' => 'Meu Card', 'content' => 'Conteúdo do card'])
```

### Código PHP Embutido

```php
@php
    $total = 0;
    foreach ($itens as $item) {
        $total += $item->preco;
    }
@endphp

<p>Total: R$ {{ number_format($total, 2, ',', '.') }}</p>
```

## Como Funciona

O Petal funciona compilando templates `.petal.php` em arquivos PHP puros que são armazenados em cache. Isso significa que você obtém o melhor dos dois mundos: a sintaxe elegante de um sistema de templates e o desempenho do PHP puro.

1. **Compilação**: Os templates são compilados apenas quando necessário (quando o arquivo original é modificado)
2. **Armazenamento em Cache**: Os templates compilados são armazenados em `storage/framework/views`
3. **Execução**: O PHP executa o template compilado, não o original

## Uso no Controlador

```php
public function index(): string
{
    return $this->view('home', [
        'title' => 'Página Inicial',
        'items' => Item::all()
    ]);
}
```

## Diretivas Personalizadas

Você pode registrar suas próprias diretivas para estender a funcionalidade do Petal:

```php
// Em um provedor de serviços ou no bootstrap
$view = $container->get(View::class);
$view->directive('@uppercase', function ($expression) {
    return "<?php echo strtoupper($expression); ?>";
});
```

Depois, você pode usar a diretiva em seus templates:

```php
@uppercase($nome)
```

## Considerações de Segurança

- Variáveis exibidas com `{{ $var }}` são automaticamente escapadas para prevenir XSS
- Use `{!! $var !!}` apenas quando tiver certeza de que o conteúdo é seguro
- Evite passar dados não confiáveis diretamente para diretivas como `@php`

## Dicas e Boas Práticas

1. **Mantenha os Templates Simples**: Evite lógica complexa nos templates
2. **Use Componentes**: Divida interfaces complexas em componentes reutilizáveis
3. **Aproveite a Herança**: Crie layouts base para manter a consistência
4. **Cache Inteligente**: O Petal só recompila templates quando necessário
5. **Organize seus Templates**: Use namespaces com pontos (ex: `admin.dashboard`) para organizar seus templates em diretórios