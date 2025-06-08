@extends('layouts.app')

@section('title', 'Facial Verification')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h1 class="card-title h4">Facial Verification</h1>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="progress mb-4" style="height: 8px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            
                            <div class="d-flex justify-content-between step-indicator">
                                <div class="step-item completed">
                                    <div class="step-number">1</div>
                                    <div class="step-label">Omang Verification</div>
                                </div>
                                <div class="step-item completed">
                                    <div class="step-number">2</div>
                                    <div class="step-label">Document Upload</div>
                                </div>
                                <div class="step-item active">
                                    <div class="step-number">3</div>
                                    <div class="step-label">Facial Verification</div>
                                </div>
                                <div class="step-item">
                                    <div class="step-number">4</div>
                                    <div class="step-label">Additional Information</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-info" role="alert">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-info-circle fa-2x"></i>
                                    </div>
                                    <div>
                                        <h4 class="alert-heading h5">Facial Verification Instructions</h4>
                                        <p class="mb-0">We'll compare your live selfie with your Omang photo to verify your identity. Please:</p>
                                        <ul class="mb-0">
                                            <li>Ensure you're in a well-lit area</li>
                                            <li>Remove glasses, hats, or other face coverings</li>
                                            <li>Look directly at the camera with a neutral expression</li>
                                            <li>Keep your face centered in the frame</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @livewire('facial-capture')

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h3 class="h6 mb-3">Why we need facial verification:</h3>
                                    <ul class="small text-muted mb-0">
                                        <li>Provides an additional layer of security for your account</li>
                                        <li>Helps prevent identity theft and fraud</li>
                                        <li>Ensures that you are the rightful owner of the provided documents</li>
                                        <li>Complies with banking regulations for customer identification</li>
                                        <li>Enables a fully digital registration process without visiting a branch</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between">
                    <a href="{{ route('verification.documents') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </a>
                    <a href="{{ route('verification.additional') }}" class="btn btn-primary" id="nextButton" 
                       style="{{ $latestSession && $latestSession->status === 'approved' ? '' : 'display: none;' }}">
                        <i class="fas fa-arrow-right me-2"></i> Next: Additional Information
                    </a>
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
                                    What if my facial verification fails?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="faqOne" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    If your facial verification fails, try again in better lighting conditions, ensure your face is clearly visible, and look directly at the camera. If you continue to experience issues, you may need to visit an FNBB branch for in-person verification. Our system requires a minimum similarity score of 70% for verification to pass.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    How is my facial data protected?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="faqTwo" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Your facial data is encrypted and securely stored in compliance with data protection regulations. We use this data only for identity verification purposes and do not share it with third parties. After verification, the data is stored in a secure environment with restricted access and is subject to our data retention policies.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    What if my appearance has changed since my Omang photo?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="faqThree" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Our facial recognition system can account for minor changes in appearance like aging, hairstyle changes, or facial hair. However, if you've undergone significant changes since your Omang photo was taken, you may need to visit an FNBB branch for in-person verification. Bringing your current Omang card will help verify your identity.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Listen for Livewire events
        window.livewire.on('verificationPassed', (sessionId) => {
            document.getElementById('nextButton').style.display = 'block';
            showToast('Facial verification successful!', 'success');
        });
        
        window.livewire.on('verificationFailed', (score) => {
            showToast('Verification failed. Please try again.', 'error');
        });
    });
    
    // Toast notification function
    function showToast(message, type = 'info') {
        // Create toast container if it doesn't exist
        if (!document.getElementById('toast-container')) {
            const toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
            toastContainer.style.zIndex = '1050';
            document.body.appendChild(toastContainer);
        }
        
        // Create unique ID for this toast
        const toastId = 'toast-' + Date.now();
        
        // Set color class based on type
        let colorClass = 'bg-info';
        if (type === 'success') colorClass = 'bg-success';
        if (type === 'warning') colorClass = 'bg-warning';
        if (type === 'error') colorClass = 'bg-danger';
        
        // Create toast HTML
        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = `toast ${colorClass} text-white`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        toast.innerHTML = `
            <div class="toast-header">
                <strong class="me-auto">FNBB Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        `;
        
        // Add toast to container
        document.getElementById('toast-container').appendChild(toast);
        
        // Initialize and show toast
        const bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: 3000
        });
        bsToast.show();
        
        // Remove toast from DOM after hiding
        toast.addEventListener('hidden.bs.toast', function() {
            document.getElementById(toastId).remove();
        });
    }
</script>
@endpush
@endsection