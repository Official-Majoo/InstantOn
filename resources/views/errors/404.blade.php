@extends('layouts.guest')

@section('title', 'Page Not Found')

@section('content')
<div class="text-center">
    <div class="error-icon mx-auto mb-4">
        <i class="fas fa-exclamation-triangle fa-4x text-warning"></i>
    </div>
    
    <h1 class="h3 mb-3">Oops! Page Not Found</h1>
    <p class="lead mb-5">We couldn't find the page you were looking for.</p>
    
    <div class="row mb-5">
        <div class="col-md-8 offset-md-2">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h2 class="h5 mb-3">Looking for something specific?</h2>
                    <div class="row text-start">
                        <div class="col-md-6 mb-3">
                            <h3 class="h6">Common Links</h3>
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('dashboard') }}" class="nav-link ps-0">
                                        <i class="fas fa-home me-2"></i> Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('registration.start') }}" class="nav-link ps-0">
                                        <i class="fas fa-user-plus me-2"></i> Registration
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('login') }}" class="nav-link ps-0">
                                        <i class="fas fa-sign-in-alt me-2"></i> Login
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h3 class="h6">Registration Steps</h3>
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('verification.omang') }}" class="nav-link ps-0">
                                        <i class="fas fa-id-card me-2"></i> Omang Verification
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('verification.documents') }}" class="nav-link ps-0">
                                        <i class="fas fa-file-alt me-2"></i> Document Upload
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('verification.facial') }}" class="nav-link ps-0">
                                        <i class="fas fa-camera me-2"></i> Facial Verification
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('verification.additional') }}" class="nav-link ps-0">
                                        <i class="fas fa-clipboard-list me-2"></i> Additional Information
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2 col-md-6 mx-auto">
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-home me-2"></i> Return to Dashboard
                </a>
                <a href="javascript:history.back()" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Go Back
                </a>
            </div>
        </div>
    </div>
    
    <div class="text-muted small">
        <p>If you continue to experience issues, please contact our customer support:</p>
        <p><i class="fas fa-phone me-1"></i> +267 370 6000 | <i class="fas fa-envelope me-1"></i> info@fnbbotswana.co.bw</p>
    </div>
</div>

<style>
.error-icon {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background-color: rgba(255, 193, 7, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection