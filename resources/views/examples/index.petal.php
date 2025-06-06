@extends('layouts.example')

@section('content')
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Exemplos do Pluma Framework</h2>
                    <p class="lead">Esta seção contém exemplos práticos para ajudar você a começar com o Pluma Framework.</p>
                    <p>Explore os diferentes exemplos para aprender como usar os recursos do framework de forma eficiente.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach($examples as $example)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="feature-icon">
                            <i class="fas {{ $example['icon'] }}"></i>
                        </div>
                        <h3 class="card-title">{{ $example['title'] }}</h3>
                        <p>{{ $example['description'] }}</p>
                        <a href="{{ $example['url'] }}" class="btn btn-outline-primary">Ver exemplo</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Sistema de Templates Petal</h3>
                </div>
                <div class="card-body">
                    <p>O Petal é o sistema de templates do Pluma Framework. Ele permite que você escreva HTML com uma sintaxe elegante e expressiva.</p>
                    
                    <h4 class="mt-4">Exemplo de Template Petal</h4>
                    <pre><code class="language-php">@php
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
@endphp</code></pre>

                    <h4 class="mt-4">Características do Petal</h4>
                    <ul class="list-group mb-4">
                        <li class="list-group-item">
                            <strong>Herança de templates</strong> - Use <code>@extends</code> e <code>@section</code> para herdar layouts
                        </li>
                        <li class="list-group-item">
                            <strong>Saída com escape</strong> - Use <code>{{ '{{ $variable }}' }}</code> para exibir dados com escape HTML
                        </li>
                        <li class="list-group-item">
                            <strong>Saída sem escape</strong> - Use <code>{!! '{!! $variable !!}' !!}</code> para exibir HTML sem escape
                        </li>
                        <li class="list-group-item">
                            <strong>Comentários</strong> - Use <code>{# 'Comentário' #}</code> para adicionar comentários
                        </li>
                        <li class="list-group-item">
                            <strong>Estruturas de controle</strong> - Use <code>@if</code>, <code>@foreach</code>, <code>@for</code>, etc.
                        </li>
                        <li class="list-group-item">
                            <strong>Inclusão de sub-templates</strong> - Use <code>@include('template')</code> para incluir outros templates
                        </li>
                    </ul>
                    
                    <a href="/examples/templates" class="btn btn-primary">Explorar mais exemplos de templates</a>
                </div>
            </div>
        </div>
    </div>
@endsection