@extends('layouts.guest')

@section('title', 'Register')

@section('content')
<div class="card shadow-sm">
    <div class="card-body p-4">
        <h1 class="h4 text-center mb-4">Create Your Account</h1>
        
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                       name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" required autocomplete="email">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                       name="password" required autocomplete="new-password">
                <div class="form-text">
                    Password must be at least 8 characters and include letters, numbers and special characters.
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password-confirm" class="form-label">Confirm Password</label>
                <input id="password-confirm" type="password" class="form-control" 
                       name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary py-2">
                    <i class="fas fa-user-plus me-2"></i> Register
                </button>
            </div>
        </form>
        
        <div class="text-center mt-4">
            <p class="mb-0">Already have an account? <a href="{{ route('login') }}" class="text-decoration-none">Log in</a></p>
        </div>
    </div>
</div>

<div class="text-center mt-4">
    <p class="text-muted small">By registering, you agree to FNBB's <a href="#" class="text-decoration-none">Terms of Service</a> and acknowledge our <a href="#" class="text-decoration-none">Privacy Policy</a>.</p>
</div>
@endsection