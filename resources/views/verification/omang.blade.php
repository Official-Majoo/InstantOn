@extends('layouts.app')

@section('title', 'Omang Verification')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h1 class="card-title h4">Omang Verification</h1>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="progress mb-4" style="height: 8px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            
                            <div class="d-flex justify-content-between step-indicator">
                                <div class="step-item active">
                                    <div class="step-number">1</div>
                                    <div class="step-label">Omang Verification</div>
                                </div>
                                <div class="step-item">
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
                    
                    <div class="row">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h2 class="h5 mb-4">Why do we need your Omang?</h2>
                                    
                                    <p class="text-muted">Your Omang (National ID) helps us verify your identity securely and accurately.</p>
                                    
                                    <ul class="text-muted">
                                        <li>Protects against identity theft and fraud</li>
                                        <li>Streamlines your account opening process</li>
                                        <li>Helps us comply with banking regulations</li>
                                        <li>Ensures we have accurate personal information</li>
                                    </ul>
                                    
                                    <p class="text-muted">Rest assured that your information is encrypted and handled with the utmost security.</p>
                                    
                                    <h3 class="h6 mt-4">What happens next?</h3>
                                    <p class="text-muted mb-0">We'll verify your Omang number with the national database. Once verified, you'll proceed to the document upload step.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h2 class="h5 mb-4">Omang Verification</h2>
                                    
                                    @if(isset($needsProfile) && $needsProfile)
                                        <!-- Initial profile creation form -->
                                        <div class="alert alert-info" role="alert">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>Let's get started!</strong>
                                            <p class="mb-0">Please enter your Omang number to begin the verification process.</p>
                                        </div>
                                        
                                        <form method="POST" action="{{ route('verification.omang.submit') }}" id="omangForm">
                                            @csrf
                                            
                                            <div class="mb-4">
                                                <label for="omang_number" class="form-label">Omang Number</label>
                                                <input type="text" class="form-control @error('omang_number') is-invalid @enderror" 
                                                       id="omang_number" name="omang_number" 
                                                       value="{{ old('omang_number') }}" 
                                                       required maxlength="9" pattern="[0-9]{9}">
                                                <div class="form-text">Enter your 9-digit Omang number without spaces.</div>
                                                @error('omang_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="form-check mb-4">
                                                <input class="form-check-input" type="checkbox" id="consent" required>
                                                <label class="form-check-label" for="consent">
                                                    I consent to FNBB verifying my Omang details with the national identity system.
                                                </label>
                                            </div>
                                            
                                            <div class="d-grid gap-2">
                                                <button type="submit" class="btn btn-primary" id="verifyButton">
                                                    <i class="fas fa-check-circle me-2"></i> Verify Omang
                                                </button>
                                            </div>
                                        </form>
                                    @elseif(isset($customerProfile) && $customerProfile->verification_status === 'verified')
                                        <div class="alert alert-success" role="alert">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <strong>Omang Verified Successfully!</strong>
                                            <p class="mb-0">Your Omang has been verified with the national system.</p>
                                        </div>
                                        
                                        <div class="customer-detail-row">
                                            <div class="customer-detail-label">Omang Number:</div>
                                            <div>{{ $customerProfile->omang_number }}</div>
                                        </div>
                                        <div class="customer-detail-row">
                                            <div class="customer-detail-label">Full Name:</div>
                                            <div>{{ $customerProfile->first_name }} {{ $customerProfile->middle_name }} {{ $customerProfile->last_name }}</div>
                                        </div>
                                        <div class="customer-detail-row">
                                            <div class="customer-detail-label">Date of Birth:</div>
                                            <div>{{ $customerProfile->date_of_birth->format('d/m/Y') }}</div>
                                        </div>
                                        <div class="customer-detail-row">
                                            <div class="customer-detail-label">Gender:</div>
                                            <div>{{ ucfirst($customerProfile->gender) }}</div>
                                        </div>
                                        
                                        <div class="d-grid gap-2 mt-4">
                                            <a href="{{ route('verification.documents') }}" class="btn btn-primary">
                                                <i class="fas fa-arrow-right me-2"></i> Proceed to Document Upload
                                            </a>
                                        </div>
                                    @elseif(isset($customerProfile))
                                        <form method="POST" action="{{ route('verification.omang.submit') }}" id="omangForm">
                                            @csrf
                                            
                                            <div class="mb-4">
                                                <label for="omang_number" class="form-label">Omang Number</label>
                                                <input type="text" class="form-control @error('omang_number') is-invalid @enderror" 
                                                       id="omang_number" name="omang_number" 
                                                       value="{{ old('omang_number', $customerProfile->omang_number) }}" 
                                                       required maxlength="9" pattern="[0-9]{9}">
                                                <div class="form-text">Enter your 9-digit Omang number without spaces.</div>
                                                @error('omang_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="form-check mb-4">
                                                <input class="form-check-input" type="checkbox" id="consent" required>
                                                <label class="form-check-label" for="consent">
                                                    I consent to FNBB verifying my Omang details with the national identity system.
                                                </label>
                                            </div>
                                            
                                            <div class="d-grid gap-2">
                                                <button type="submit" class="btn btn-primary" id="verifyButton">
                                                    <i class="fas fa-check-circle me-2"></i> Verify Omang
                                                </button>
                                            </div>
                                        </form>
                                        
                                        <div class="mt-4 text-center">
                                            <p class="text-muted mb-0 small">Having trouble with your Omang verification? <a href="#" data-bs-toggle="modal" data-bs-target="#helpModal">Get help</a></p>
                                        </div>
                                    @else
                                        <!-- Fallback for any unexpected state -->
                                        <div class="alert alert-warning" role="alert">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <strong>We need some information</strong>
                                            <p class="mb-0">Please enter your Omang number to begin the verification process.</p>
                                        </div>
                                        
                                        <form method="POST" action="{{ route('verification.omang.submit') }}" id="omangForm">
                                            @csrf
                                            
                                            <div class="mb-4">
                                                <label for="omang_number" class="form-label">Omang Number</label>
                                                <input type="text" class="form-control @error('omang_number') is-invalid @enderror" 
                                                       id="omang_number" name="omang_number" 
                                                       value="{{ old('omang_number') }}" 
                                                       required maxlength="9" pattern="[0-9]{9}">
                                                <div class="form-text">Enter your 9-digit Omang number without spaces.</div>
                                                @error('omang_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="form-check mb-4">
                                                <input class="form-check-input" type="checkbox" id="consent" required>
                                                <label class="form-check-label" for="consent">
                                                    I consent to FNBB verifying my Omang details with the national identity system.
                                                </label>
                                            </div>
                                            
                                            <div class="d-grid gap-2">
                                                <button type="submit" class="btn btn-primary" id="verifyButton">
                                                    <i class="fas fa-check-circle me-2"></i> Verify Omang
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
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
                                    What if my Omang verification fails?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="faqOne" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    If your Omang verification fails, please ensure you've entered the correct Omang number without spaces or special characters. If you continue to experience issues, you may visit any FNBB branch with your physical Omang card for in-person verification, or contact our customer support at +267 370 6000 for assistance.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    How is my personal information protected?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="faqTwo" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    FNBB employs industry-standard encryption and security measures to protect your personal information. All data is transmitted securely and stored in compliance with Botswana's data protection regulations. We do not share your information with third parties except as required by law or with your explicit consent.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    What if I recently renewed my Omang?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="faqThree" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    If you've recently renewed your Omang, use your new Omang number for verification. In some cases, there may be a delay in the national database update. If verification fails, you can try again in a few days or visit an FNBB branch with both your old and new Omang cards for assistance.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="helpModalLabel">Omang Verification Help</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Common Issues:</h6>
                <ul>
                    <li><strong>Incorrect Format:</strong> Ensure your Omang number is 9 digits without spaces or special characters.</li>
                    <li><strong>Recently Renewed Omang:</strong> There may be a delay in the national database. Try again in a few days or visit a branch.</li>
                    <li><strong>Technical Issues:</strong> Occasionally, the verification system may experience temporary outages. Try again later.</li>
                </ul>
                
                <h6 class="mt-4">Need Further Assistance?</h6>
                <p>Contact our customer support team:</p>
                <ul>
                    <li>Phone: +267 370 6000</li>
                    <li>Email: info@fnbbotswana.co.bw</li>
                    <li>Visit any FNBB branch with your Omang card</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="tel:+267-370-6000" class="btn btn-primary">
                    <i class="fas fa-phone me-2"></i> Call Support
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const omangInput = document.getElementById('omang_number');
        
        if (omangInput) {
            // Format Omang number as digits only
            omangInput.addEventListener('input', function(e) {
                const value = e.target.value.replace(/\D/g, '');
                e.target.value = value;
            });
            
            // Add loading state to form on submit
            const form = document.getElementById('omangForm');
            const verifyButton = document.getElementById('verifyButton');
            
            if (form) {
                form.addEventListener('submit', function() {
                    verifyButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Verifying...';
                    verifyButton.disabled = true;
                });
            }
        }
    });
</script>
@endpush
@endsection