@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h1>{{ $title }}</h1>
        </div>
        <div class="card-body">
            <p class="lead">{{ $message }}</p>
            
            <h2>Features</h2>
            
            <div class="row">
                @foreach($features as $feature)
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $feature['title'] }}</h5>
                                <p class="card-text">{{ $feature['description'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <h2 class="mt-4">Code Example</h2>
            
            <pre class="bg-light p-3 rounded"><code>// Example controller code
class HomeController extends Controller
{
    public function index()
    {
        return $this->view('welcome', [
            'title' => 'Welcome to Pluma',
            'message' => 'A modern PHP framework with powerful features'
        ]);
    }
}</code></pre>
            
            @if($showButton)
                <a href="/docs" class="btn btn-primary mt-3">Read the Documentation</a>
            @endif
        </div>
    </div>
@endsection

@section('styles')
<style>
    pre {
        border: 1px solid #ddd;
    }
    
    .card-title {
        color: #3490dc;
    }
</style>
@endsection