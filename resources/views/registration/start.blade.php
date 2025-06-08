@extends('layouts.app')

@section('title', 'Start Registration')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h1 class="card-title h4">FNBB Online Account Registration</h1>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-5">
                        <div class="bg-light-gray rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-user-plus fa-2x text-primary"></i>
                        </div>
                        <h2 class="h4">Welcome to FNBB Online Registration</h2>
                        <p class="text-muted">Complete your registration quickly and securely from anywhere.</p>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <div class="dashboard-icon primary mx-auto">
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                    <h3 class="h5 mb-3">Omang Verification</h3>
                                    <p class="text-muted small mb-0">Your Omang details will be verified securely with the Botswana national identity system.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4 mb-md-0">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <div class="dashboard-icon tertiary mx-auto">
                                        <i class="fas fa-camera"></i>
                                    </div>
                                    <h3 class="h5 mb-3">Facial Verification</h3>
                                    <p class="text-muted small mb-0">We'll compare your selfie with your Omang photo to confirm your identity.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <div class="dashboard-icon success mx-auto">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <h3 class="h5 mb-3">Officer Review</h3>
                                    <p class="text-muted small mb-0">Our bank officers will review your application to ensure everything is in order.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info d-flex" role="alert">
                        <i class="fas fa-info-circle me-3 fa-lg mt-1"></i>
                        <div>
                            <h4 class="alert-heading h5">Before you start</h4>
                            <p class="mb-0">Please ensure you have the following ready:</p>
                            <ul class="mb-0">
                                <li>Your Omang (National ID) card</li>
                                <li>Proof of address (utility bill, bank statement, etc.)</li>
                                <li>A device with a camera for facial verification</li>
                            </ul>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        @if(Auth::check())
                            <a href="{{ route('verification.omang') }}" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-arrow-right me-2"></i> Start Registration
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-user-plus me-2"></i> Create Account
                            </a>
                            <p class="mt-3 text-muted">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card mt-4 shadow-sm">
                <div class="card-header bg-white">
                    <h2 class="card-title h5">Frequently Asked Questions</h2>
                </div>
                <div class="card-body">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    How long does the registration process take?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="faqOne" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    The entire process typically takes about 15-20 minutes to complete if you have all required documents ready. After submission, an FNBB officer will review your application within 1-2 business days.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Is my information secure?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="faqTwo" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes, FNBB uses industry-standard encryption and security measures to protect your personal information. Your data is transmitted securely and stored in compliance with financial industry regulations.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    What if my Omang verification fails?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="faqThree" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    If your Omang verification fails, the system will provide specific reasons. You can try again or visit any FNBB branch with your physical Omang card for in-person verification.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    Can I save my progress and continue later?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="faqFour" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes, your progress is automatically saved at each step. You can log out and return later to continue from where you left off.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection