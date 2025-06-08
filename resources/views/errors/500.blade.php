@extends('layouts.guest')

@section('title', 'Server Error')

@section('content')
<div class="text-center">
    <div class="error-icon mx-auto mb-4">
        <i class="fas fa-exclamation-circle fa-4x text-danger"></i>
    </div>
    
    <h1 class="h3 mb-3">Oops! Something Went Wrong</h1>
    <p class="lead mb-5">We're experiencing some technical difficulties. Please try again later.</p>
    
    <div class="row mb-5">
        <div class="col-md-8 offset-md-2">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h2 class="h5 mb-3">What can you do?</h2>
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="d-flex">
                                <div class="me-3">
                                    <div class="feature-icon error">
                                        <i class="fas fa-redo"></i>
                                    </div>
                                </div>
                                <div class="text-start">
                                    <h3 class="h6">Try Again Later</h3>
                                    <p class="text-muted small mb-0">This is likely a temporary issue. Please try again in a few minutes.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="me-3">
                                    <div class="feature-icon error">
                                        <i class="fas fa-headset"></i>
                                    </div>
                                </div>
                                <div class="text-start">
                                    <h3 class="h6">Contact Support</h3>
                                    <p class="text-muted small mb-0">If the problem persists, please contact our support team for assistance.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2 col-md-6 mx-auto">
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-home me-2"></i> Return to Dashboard
                </a>
                <a href="javascript:location.reload()" class="btn btn-outline-secondary">
                    <i class="fas fa-redo me-2"></i> Refresh Page
                </a>
            </div>
        </div>
    </div>
    
    <div class="text-muted small">
        <p>If you continue to experience issues, please contact our technical support:</p>
        <p><i class="fas fa-phone me-1"></i> +267 370 6000 | <i class="fas fa-envelope me-1"></i> support@fnbbotswana.co.bw</p>
        <p class="small">Error Reference: {{ Str::random(8) }}</p>
    </div>
</div>

<style>
.error-icon {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background-color: rgba(220, 53, 69, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.feature-icon.error {
    background-color: rgba(220, 53, 69, 0.1);
    color: var(--fnbb-danger);
}
</style>
@endsection