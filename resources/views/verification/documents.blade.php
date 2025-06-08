@extends('layouts.app')

@section('title', 'Document Upload')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h1 class="card-title h4">Document Upload</h1>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="progress mb-4" style="height: 8px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            
                            <div class="d-flex justify-content-between step-indicator">
                                <div class="step-item completed">
                                    <div class="step-number">1</div>
                                    <div class="step-label">Omang Verification</div>
                                </div>
                                <div class="step-item active">
                                    <div class="step-number">2</div>
                                    <div class="step-label">Document Upload</div>
                                </div>
                                <div class="step-item">
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
                                        <h4 class="alert-heading h5">Required Documents</h4>
                                        <p class="mb-0">Please upload clear, legible photos or scans of the following documents:</p>
                                        <ul class="mb-0">
                                            <li>Front side of your Omang</li>
                                            <li>Back side of your Omang</li>
                                            <li>Proof of Address (utility bill, lease agreement, etc. not older than 3 months)</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @livewire('document-upload')

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h3 class="h6 mb-3">Document Guidelines:</h3>
                                    <ul class="small text-muted mb-0">
                                        <li>Files must be JPG, JPEG, PNG or PDF format</li>
                                        <li>Maximum file size: 5MB per document</li>
                                        <li>Documents must be clear, legible, and uncropped</li>
                                        <li>Ensure all corners and edges of the documents are visible</li>
                                        <li>Avoid glare, shadows, or blurry images</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between">
                    <a href="{{ route('verification.omang') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </a>
                    <a href="{{ route('verification.facial') }}" class="btn btn-primary" id="nextButton">
                        <i class="fas fa-arrow-right me-2"></i> Next: Facial Verification
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Listen for Livewire events
        window.livewire.on('documentUploaded', (type) => {
            showToast('Document uploaded successfully!', 'success');
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