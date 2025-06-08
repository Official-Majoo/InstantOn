@extends('layouts.app')

@section('title', 'Additional Information')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h1 class="card-title h4">Additional Information</h1>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="progress mb-4" style="height: 8px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
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
                                <div class="step-item completed">
                                    <div class="step-number">3</div>
                                    <div class="step-label">Facial Verification</div>
                                </div>
                                <div class="step-item active">
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
                                        <h4 class="alert-heading h5">Final Step</h4>
                                        <p class="mb-0">Please provide the following additional information to complete your registration. This information helps us understand your financial profile and serve you better.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('verification.additional.submit') }}" id="additionalInfoForm">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h2 class="h5 mb-3">Residential Information</h2>
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="address" class="form-label">Physical Address</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                       id="address" name="address" value="{{ old('address', $customerProfile->address) }}" required>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="postal_code" class="form-label">Postal Code</label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                       id="postal_code" name="postal_code" value="{{ old('postal_code', $customerProfile->postal_code) }}" required>
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">City/Town</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city', $customerProfile->city) }}" required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="district" class="form-label">District</label>
                                <select class="form-select @error('district') is-invalid @enderror" 
                                        id="district" name="district" required>
                                    <option value="" selected disabled>Select district</option>
                                    <option value="Gaborone" {{ old('district', $customerProfile->district) == 'Gaborone' ? 'selected' : '' }}>Gaborone</option>
                                    <option value="Francistown" {{ old('district', $customerProfile->district) == 'Francistown' ? 'selected' : '' }}>Francistown</option>
                                    <option value="Molepolole" {{ old('district', $customerProfile->district) == 'Molepolole' ? 'selected' : '' }}>Molepolole</option>
                                    <option value="Serowe" {{ old('district', $customerProfile->district) == 'Serowe' ? 'selected' : '' }}>Serowe</option>
                                    <option value="Maun" {{ old('district', $customerProfile->district) == 'Maun' ? 'selected' : '' }}>Maun</option>
                                    <option value="Kanye" {{ old('district', $customerProfile->district) == 'Kanye' ? 'selected' : '' }}>Kanye</option>
                                    <option value="Mahalapye" {{ old('district', $customerProfile->district) == 'Mahalapye' ? 'selected' : '' }}>Mahalapye</option>
                                    <option value="Mogoditshane" {{ old('district', $customerProfile->district) == 'Mogoditshane' ? 'selected' : '' }}>Mogoditshane</option>
                                    <option value="Mochudi" {{ old('district', $customerProfile->district) == 'Mochudi' ? 'selected' : '' }}>Mochudi</option>
                                    <option value="Lobatse" {{ old('district', $customerProfile->district) == 'Lobatse' ? 'selected' : '' }}>Lobatse</option>
                                    <option value="Palapye" {{ old('district', $customerProfile->district) == 'Palapye' ? 'selected' : '' }}>Palapye</option>
                                    <option value="Ramotswa" {{ old('district', $customerProfile->district) == 'Ramotswa' ? 'selected' : '' }}>Ramotswa</option>
                                    <option value="Other" {{ old('district', $customerProfile->district) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h2 class="h5 mb-3">Employment Information</h2>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="occupation" class="form-label">Occupation</label>
                                <input type="text" class="form-control @error('occupation') is-invalid @enderror" 
                                       id="occupation" name="occupation" value="{{ old('occupation', $customerProfile->occupation) }}" required>
                                @error('occupation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="employer" class="form-label">Employer (if applicable)</label>
                                <input type="text" class="form-control @error('employer') is-invalid @enderror" 
                                       id="employer" name="employer" value="{{ old('employer', $customerProfile->employer) }}">
                                @error('employer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="income_range" class="form-label">Monthly Income Range</label>
                                <select class="form-select @error('income_range') is-invalid @enderror" 
                                        id="income_range" name="income_range" required>
                                    <option value="" selected disabled>Select income range</option>
                                    <option value="below_5000" {{ old('income_range', $customerProfile->income_range) == 'below_5000' ? 'selected' : '' }}>Below 5,000 BWP</option>
                                    <option value="5000_to_10000" {{ old('income_range', $customerProfile->income_range) == '5000_to_10000' ? 'selected' : '' }}>5,000 - 10,000 BWP</option>
                                    <option value="10001_to_25000" {{ old('income_range', $customerProfile->income_range) == '10001_to_25000' ? 'selected' : '' }}>10,001 - 25,000 BWP</option>
                                    <option value="25001_to_50000" {{ old('income_range', $customerProfile->income_range) == '25001_to_50000' ? 'selected' : '' }}>25,001 - 50,000 BWP</option>
                                    <option value="above_50000" {{ old('income_range', $customerProfile->income_range) == 'above_50000' ? 'selected' : '' }}>Above 50,000 BWP</option>
                                </select>
                                @error('income_range')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="termsAccepted" required>
                                    <label class="form-check-label" for="termsAccepted">
                                        I confirm that the information provided is accurate and complete. I have read and agree to FNBB's 
                                        <a href="#" target="_blank">Terms and Conditions</a> and 
                                        <a href="#" target="_blank">Privacy Policy</a>.
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-warning" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Important:</strong> After submission, your application will be reviewed by an FNBB officer. This process typically takes 1-2 business days.
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between">
                    <a href="{{ route('verification.facial') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </a>
                    <button type="submit" form="additionalInfoForm" class="btn btn-primary" id="submitButton">
                        <i class="fas fa-check-circle me-2"></i> Complete Registration
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add loading state to form on submit
        const form = document.getElementById('additionalInfoForm');
        const submitButton = document.getElementById('submitButton');
        
        if (form) {
            form.addEventListener('submit', function() {
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Submitting...';
                submitButton.disabled = true;
            });
        }
    });
</script>
@endpush
@endsection