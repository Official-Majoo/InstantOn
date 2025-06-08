@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="card shadow-sm">
    <div class="card-body p-4">
        <h1 class="h4 text-center mb-4">Log in to Your Account</h1>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <label for="password" class="form-label">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-decoration-none small">
                            Forgot your password?
                        </a>
                    @endif
                </div>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                       name="password" required autocomplete="current-password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="mb-4 form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    Remember me
                </label>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary py-2">
                    <i class="fas fa-sign-in-alt me-2"></i> Log in
                </button>
            </div>
        </form>
        
        <div class="text-center mt-4">
            <p class="mb-0">Don't have an account? <a href="{{ route('registration.start') }}" class="text-decoration-none">Register now</a></p>
        </div>
    </div>
</div>

<div class="text-center mt-4">
    <p class="text-muted small">By continuing, you agree to FNBB's <a href="#" class="text-decoration-none">Terms of Service</a> and acknowledge our <a href="#" class="text-decoration-none">Privacy Policy</a>.</p>
</div>
@endsection