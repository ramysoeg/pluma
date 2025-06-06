@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h1>{{ $title }}</h1>
        </div>
        <div class="card-body">
            @if(isset($user))
                <div class="row">
                    <div class="col-md-4">
                        <img src="https://via.placeholder.com/150" alt="{{ $user['name'] }}" class="img-fluid rounded-circle mb-3">
                    </div>
                    <div class="col-md-8">
                        <h2>{{ $user['name'] }}</h2>
                        <p><strong>Email:</strong> {{ $user['email'] }}</p>
                        <p><strong>User ID:</strong> {{ $user['id'] }}</p>
                        <p><strong>Member since:</strong> {{ $user['created_at'] }}</p>
                        
                        <div class="mt-4">
                            <a href="/" class="btn btn-primary">Back to Home</a>
                            
                            @if($user['id'] > 1)
                                <a href="/profile/{{ $user['id'] - 1 }}" class="btn btn-outline-secondary">Previous User</a>
                            @endif
                            
                            <a href="/profile/{{ $user['id'] + 1 }}" class="btn btn-outline-secondary">Next User</a>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h3>Recent Activity</h3>
                    
                    <ul class="list-group">
                        @for($i = 1; $i <= 5; $i++)
                            <li class="list-group-item">
                                Activity {{ $i }} - {{ date('Y-m-d', strtotime("-{$i} days")) }}
                            </li>
                        @endfor
                    </ul>
                </div>
            @else
                <div class="alert alert-warning">
                    User with ID {{ $id }} not found.
                </div>
                
                <a href="/" class="btn btn-primary">Back to Home</a>
            @endif
        </div>
    </div>
@endsection

@section('styles')
<style>
    .rounded-circle {
        border: 3px solid #3490dc;
    }
</style>
@endsection