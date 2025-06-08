@extends('layouts.app')

@section('title', 'Registration Success')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <div class="success-icon mx-auto">
                            <i class="fas fa-check-circle fa-5x text-success"></i>
                        </div>
                    </div>
                    
                    <h1 class="h3 mb-3">Registration Submitted Successfully!</h1>
                    <p class="lead mb-4">Thank you for completing your FNBB online registration.</p>
                    
                    <div class="alert alert-info mb-4" role="alert">
                        <h2 class="h5">What happens next?</h2>
                        <p class="mb-0">Your application is now being reviewed by our bank officers. This process typically takes 1-2 business days. You'll receive updates on your verification status via email and SMS.</p>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="dashboard-icon primary mx-auto">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <h3 class="h5 mt-3">Your Account Details</h3>
                                    <div class="customer-detail-row">
                                        <div class="customer-detail-label">Name:</div>
                                        <div>{{ $customerProfile->first_name }} {{ $customerProfile->last_name }}</div>
                                    </div>
                                    <div class="customer-detail-row">
                                        <div class="customer-detail-label">Email:</div>
                                        <div>{{ Auth::user()->email }}</div>
                                    </div>
                                    <div class="customer-detail-row">
                                        <div class="customer-detail-label">Registration Date:</div>
                                        <div>{{ now()->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="dashboard-icon success mx-auto">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <h3 class="h5 mt-3">Need Help?</h3>
                                    <p class="text-muted small mb-3">If you have any questions or need assistance, our customer support team is ready to help.</p>
                                    <div class="d-grid gap-2">
                                        <a href="tel:+267-370-6000" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-phone me-2"></i> +267 370 6000
                                        </a>
                                        <a href="mailto:info@fnbbotswana.co.bw" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-envelope me-2"></i> Email Support
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 col-md-6 mx-auto">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i> Go to Dashboard
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4 shadow-sm">
                <div class="card-header bg-white">
                    <h2 class="card-title h5">While You Wait</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="text-center">
                                <i class="fas fa-mobile-alt fa-2x text-primary mb-3"></i>
                                <h3 class="h6">Download the FNB App</h3>
                                <p class="text-muted small">Get access to banking on the go with our mobile app.</p>
                                <div class="app-download-buttons">
                                    <a href="#" class="me-2">
                                        <img src="{{ asset('images/app-store-badge.png') }}" alt="App Store" height="50">
                                    </a>
                                    <a href="#">
                                        <img src="{{ asset('images/google-play-badge.png') }}" alt="Google Play" height="35">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="text-center">
                                <i class="fas fa-university fa-2x text-primary mb-3"></i>
                                <h3 class="h6">Explore Our Products</h3>
                                <p class="text-muted small">Discover our range of accounts, loans, and investment options.</p>
                                <a href="#" class="btn btn-sm btn-outline-primary">Learn More</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-headset fa-2x text-primary mb-3"></i>
                                <h3 class="h6">Help & Support</h3>
                                <p class="text-muted small">Find answers to common questions in our help center.</p>
                                <a href="#" class="btn btn-sm btn-outline-primary">Visit Help Center</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.success-icon {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background-color: rgba(40, 167, 69, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 2rem;
}

.customer-detail-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.customer-detail-label {
    font-weight: 600;
    color: var(--fnbb-text-light);
}
</style>
@endsection