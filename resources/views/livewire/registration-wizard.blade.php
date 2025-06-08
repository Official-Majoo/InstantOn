<div>
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h1 class="card-title h4">FNBB Online Registration</h1>
        </div>
        <div class="card-body p-4">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="progress mb-4" style="height: 8px;">
                        <div class="progress-bar bg-primary" role="progressbar" 
                             style="width: {{ ($step / $totalSteps) * 100 }}%;" 
                             aria-valuenow="{{ ($step / $totalSteps) * 100 }}" 
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    
                    <div class="d-flex justify-content-between step-indicator">
                        <div class="step-item {{ $step >= 1 ? 'active' : '' }}">
                            <div class="step-number">1</div>
                            <div class="step-label">Account Setup</div>
                        </div>
                        <div class="step-item {{ $step >= 2 ? 'active' : '' }}">
                            <div class="step-number">2</div>
                            <div class="step-label">Personal Information</div>
                        </div>
                        <div class="step-item {{ $step >= 3 ? 'active' : '' }}">
                            <div class="step-number">3</div>
                            <div class="step-label">Omang Verification</div>
                        </div>
                        <div class="step-item {{ $step >= 4 ? 'active' : '' }}">
                            <div class="step-number">4</div>
                            <div class="step-label">Document Upload</div>
                        </div>
                        <div class="step-item {{ $step >= 5 ? 'active' : '' }}">
                            <div class="step-number">5</div>
                            <div class="step-label">Additional Information</div>
                        </div>
                    </div>
                </div>
            </div>
            
            @if($message)
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ $message }}
                </div>
            @endif
            
            <!-- Step 1: Account Setup -->
            <div class="registration-step {{ $step == 1 ? 'active' : '' }}">
                <h2 class="h5 mb-4">Account Setup</h2>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" wire:model="email" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" wire:model="password" required>
                        <div class="form-text">
                            Password must be at least 8 characters and include uppercase, lowercase, numbers, and special characters.
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" wire:model="password_confirmation" required>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-12">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text">+267</span>
                            <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" 
                                   id="phone_number" wire:model="phone_number" required 
                                   placeholder="e.g., 71234567">
                        </div>
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="terms" required>
                    <label class="form-check-label" for="terms">
                        I agree to FNBB's <a href="#" target="_blank">Terms and Conditions</a> and 
                        <a href="#" target="_blank">Privacy Policy</a>
                    </label>
                </div>
            </div>
            
            <!-- Step 2: Personal Information -->
            <div class="registration-step {{ $step == 2 ? 'active' : '' }}">
                <h2 class="h5 mb-4">Personal Information</h2>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                               id="first_name" wire:model="first_name" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="middle_name" class="form-label">Middle Name <span class="text-muted">(optional)</span></label>
                        <input type="text" class="form-control @error('middle_name') is-invalid @enderror" 
                               id="middle_name" wire:model="middle_name">
                        @error('middle_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                               id="last_name" wire:model="last_name" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                               id="date_of_birth" wire:model="date_of_birth" required>
                        @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select @error('gender') is-invalid @enderror" 
                                id="gender" wire:model="gender" required>
                            <option value="" selected disabled>Select gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-12">
                        <label for="omang_number" class="form-label">Omang Number</label>
                        <input type="text" class="form-control @error('omang_number') is-invalid @enderror" 
                               id="omang_number" wire:model="omang_number" required maxlength="9" 
                               placeholder="Enter your 9-digit Omang number">
                        <div class="form-text">Enter your 9-digit Omang number without spaces.</div>
                        @error('omang_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="alert alert-info d-flex" role="alert">
                    <i class="fas fa-info-circle me-3 fa-lg mt-1"></i>
                    <div>
                        <p class="mb-0">Your Omang information will be verified with the national database in the next step.</p>
                    </div>
                </div>
            </div>
            
            <!-- Steps 3-5 will be handled by separate controllers/views after registration -->
        </div>
        <div class="card-footer bg-white d-flex justify-content-between">
            @if($step > 1)
                <button type="button" class="btn btn-outline-secondary" wire:click="previousStep">
                    <i class="fas fa-arrow-left me-2"></i> Back
                </button>
            @else
                <div></div> <!-- Empty div to maintain flex spacing -->
            @endif
            
            <button type="button" class="btn btn-primary" wire:click="nextStep">
                @if($step < 2)
                    Next <i class="fas fa-arrow-right ms-2"></i>
                @else
                    Register <i class="fas fa-user-plus ms-2"></i>
                @endif
            </button>
        </div>
    </div>
    
    <div class="card mt-4 shadow-sm">
        <div class="card-header bg-white">
            <h2 class="card-title h5">Why Register Online?</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="d-flex">
                        <div class="me-3">
                            <div class="feature-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="h6">Save Time</h3>
                            <p class="text-muted small mb-0">Register quickly from anywhere, avoiding branch queues.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="d-flex">
                        <div class="me-3">
                            <div class="feature-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="h6">Secure Process</h3>
                            <p class="text-muted small mb-0">Advanced verification ensures your identity is protected.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="d-flex">
                        <div class="me-3">
                            <div class="feature-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="h6">Faster Approval</h3>
                            <p class="text-muted small mb-0">Get your account approved in as little as 24 hours.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.feature-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: rgba(0, 162, 165, 0.1);
    color: var(--fnbb-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Phone number validation
        const phoneInput = document.getElementById('phone_number');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                // Remove non-digits
                let value = e.target.value.replace(/\D/g, '');
                
                // Format if necessary (e.g., add spaces, etc.)
                // For now, just keep digits
                
                e.target.value = value;
            });
        }
        
        // Omang number validation
        const omangInput = document.getElementById('omang_number');
        if (omangInput) {
            omangInput.addEventListener('input', function(e) {
                // Remove non-digits
                let value = e.target.value.replace(/\D/g, '');
                
                // Limit to 9 digits
                if (value.length > 9) {
                    value = value.substring(0, 9);
                }
                
                e.target.value = value;
            });
        }
    });
</script>
@endpush