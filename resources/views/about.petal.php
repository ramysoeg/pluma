@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h1>{{ $title }}</h1>
        </div>
        <div class="card-body">
            <p class="lead">{{ $message }}</p>
            
            <h2>About Petal Templates</h2>
            
            <p>Petal is a powerful templating engine for the Pluma Framework that allows you to write clean, elegant templates with features like:</p>
            
            <ul class="list-group mb-4">
                <li class="list-group-item">Template inheritance with @extends and @section directives</li>
                <li class="list-group-item">Output escaping with {{ $variable }} syntax</li>
                <li class="list-group-item">Raw output with {!! $variable !!} syntax</li>
                <li class="list-group-item">Control structures (@if, @foreach, @while, etc.)</li>
                <li class="list-group-item">Including sub-templates with @include</li>
                <li class="list-group-item">Comments with {# This is a comment #}</li>
                <li class="list-group-item">Custom directives</li>
            </ul>
            
            <h3>Example of Conditional Logic</h3>
            
            @if($showExample)
                <div class="alert alert-success">
                    This content is shown conditionally using the @if directive.
                </div>
            @else
                <div class="alert alert-warning">
                    The example is hidden. Set $showExample to true to see it.
                </div>
            @endif
            
            <h3>Example of Loops</h3>
            
            <div class="row">
                @foreach($team as $member)
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $member['name'] }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">{{ $member['role'] }}</h6>
                                <p class="card-text">{{ $member['bio'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection