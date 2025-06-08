@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="mb-4">
                <h1 class="h3 mb-1">Profile Settings</h1>
                <p class="text-muted">Manage your account settings and security preferences.</p>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <ul class="nav nav-tabs card-header-tabs" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-info" type="button" role="tab" aria-controls="profile-info" aria-selected="true">
                                <i class="fas fa-user me-2"></i> Profile Information
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#update-password" type="button" role="tab" aria-controls="update-password" aria-selected="false">
                                <i class="fas fa-lock me-2"></i> Update Password
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security-settings" type="button" role="tab" aria-controls="security-settings" aria-selected="false">
                                <i class="fas fa-shield-alt me-2"></i> Security
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="profileTabsContent">
                        <!-- Profile Information Tab -->
                        <div class="tab-pane fade show active" id="profile-info" role="tabpanel" aria-labelledby="profile-tab">
                            <form method="POST" action="{{ route('profile.update') }}" class="mt-2">
                                @csrf
                                @method('PATCH')

                                <!-- Name -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                                               name="name" value="{{ old('name', $user->name) }}" required autofocus>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone_number" class="form-label">Phone Number</label>
                                        <div class="input-group">
                                            <span class="input-group-text">+267</span>
                                            <input id="phone_number" type="tel" class="form-control @error('phone_number') is-invalid @enderror" 
                                                   name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required>
                                            @error('phone_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="email" class="form-label">Email</label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                               name="email" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                        @if(!$user->hasVerifiedEmail())
                                            <div class="alert alert-warning mt-2 d-flex align-items-center" role="alert">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <div>
                                                    Your email address is not verified.
                                                    <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">Click here to resend verification email.</button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Address Information -->
                                @if($user->customerProfile)
                                <hr class="my-4">
                                <h4 class="h5 mb-3">Address Information</h4>
                                
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="address" class="form-label">Physical Address</label>
                                        <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                               id="address" name="address" value="{{ old('address', $user->customerProfile->address) }}">
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="postal_code" class="form-label">Postal Code</label>
                                        <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                               id="postal_code" name="postal_code" value="{{ old('postal_code', $user->customerProfile->postal_code) }}">
                                        @error('postal_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="city" class="form-label">City/Town</label>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                               id="city" name="city" value="{{ old('city', $user->customerProfile->city) }}">
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="district" class="form-label">District</label>
                                        <select class="form-select @error('district') is-invalid @enderror" 
                                                id="district" name="district">
                                            <option value="" selected disabled>Select district</option>
                                            <option value="Gaborone" {{ old('district', $user->customerProfile->district) == 'Gaborone' ? 'selected' : '' }}>Gaborone</option>
                                            <option value="Francistown" {{ old('district', $user->customerProfile->district) == 'Francistown' ? 'selected' : '' }}>Francistown</option>
                                            <option value="Molepolole" {{ old('district', $user->customerProfile->district) == 'Molepolole' ? 'selected' : '' }}>Molepolole</option>
                                            <option value="Serowe" {{ old('district', $user->customerProfile->district) == 'Serowe' ? 'selected' : '' }}>Serowe</option>
                                            <option value="Maun" {{ old('district', $user->customerProfile->district) == 'Maun' ? 'selected' : '' }}>Maun</option>
                                            <option value="Kanye" {{ old('district', $user->customerProfile->district) == 'Kanye' ? 'selected' : '' }}>Kanye</option>
                                            <option value="Mahalapye" {{ old('district', $user->customerProfile->district) == 'Mahalapye' ? 'selected' : '' }}>Mahalapye</option>
                                            <option value="Mogoditshane" {{ old('district', $user->customerProfile->district) == 'Mogoditshane' ? 'selected' : '' }}>Mogoditshane</option>
                                            <option value="Mochudi" {{ old('district', $user->customerProfile->district) == 'Mochudi' ? 'selected' : '' }}>Mochudi</option>
                                            <option value="Lobatse" {{ old('district', $user->customerProfile->district) == 'Lobatse' ? 'selected' : '' }}>Lobatse</option>
                                            <option value="Palapye" {{ old('district', $user->customerProfile->district) == 'Palapye' ? 'selected' : '' }}>Palapye</option>
                                            <option value="Ramotswa" {{ old('district', $user->customerProfile->district) == 'Ramotswa' ? 'selected' : '' }}>Ramotswa</option>
                                            <option value="Other" {{ old('district', $user->customerProfile->district) == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('district')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Occupation Information -->
                                <hr class="my-4">
                                <h4 class="h5 mb-3">Occupation Information</h4>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="occupation" class="form-label">Occupation</label>
                                        <input type="text" class="form-control @error('occupation') is-invalid @enderror" 
                                               id="occupation" name="occupation" value="{{ old('occupation', $user->customerProfile->occupation) }}">
                                        @error('occupation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="employer" class="form-label">Employer (if applicable)</label>
                                        <input type="text" class="form-control @error('employer') is-invalid @enderror" 
                                               id="employer" name="employer" value="{{ old('employer', $user->customerProfile->employer) }}">
                                        @error('employer')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="income_range" class="form-label">Monthly Income Range</label>
                                        <select class="form-select @error('income_range') is-invalid @enderror" 
                                                id="income_range" name="income_range">
                                            <option value="" selected disabled>Select income range</option>
                                            <option value="below_5000" {{ old('income_range', $user->customerProfile->income_range) == 'below_5000' ? 'selected' : '' }}>Below 5,000 BWP</option>
                                            <option value="5000_to_10000" {{ old('income_range', $user->customerProfile->income_range) == '5000_to_10000' ? 'selected' : '' }}>5,000 - 10,000 BWP</option>
                                            <option value="10001_to_25000" {{ old('income_range', $user->customerProfile->income_range) == '10001_to_25000' ? 'selected' : '' }}>10,001 - 25,000 BWP</option>
                                            <option value="25001_to_50000" {{ old('income_range', $user->customerProfile->income_range) == '25001_to_50000' ? 'selected' : '' }}>25,001 - 50,000 BWP</option>
                                            <option value="above_50000" {{ old('income_range', $user->customerProfile->income_range) == 'above_50000' ? 'selected' : '' }}>Above 50,000 BWP</option>
                                        </select>
                                        @error('income_range')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                @endif

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Update Password Tab -->
                        <div class="tab-pane fade" id="update-password" role="tabpanel" aria-labelledby="password-tab">
                            <form method="POST" action="{{ route('password.update') }}" class="mt-2" id="update-password-form">
                                @csrf
                                @method('PUT')
                                
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="current_password" class="form-label">Current Password</label>
                                        <div class="input-group">
                                            <input id="current_password" type="password" 
                                                   class="form-control @error('current_password') is-invalid @enderror" 
                                                   name="current_password" required>
                                            <button class="btn btn-outline-secondary password-toggle" type="button" data-target="current_password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="password" class="form-label">New Password</label>
                                        <div class="input-group">
                                            <input id="password" type="password" 
                                                   class="form-control @error('password') is-invalid @enderror" 
                                                   name="password" required>
                                            <button class="btn btn-outline-secondary password-toggle" type="button" data-target="password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="form-text">
                                            Password must be at least 8 characters and include letters, numbers and special characters.
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                        <div class="input-group">
                                            <input id="password_confirmation" type="password" 
                                                   class="form-control" 
                                                   name="password_confirmation" required>
                                            <button class="btn btn-outline-secondary password-toggle" type="button" data-target="password_confirmation">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="password-strength mt-3 mb-4">
                                    <label class="form-label">Password Strength</label>
                                    <div class="progress" style="height: 10px;">
                                        <div id="password-strength-meter" class="progress-bar bg-danger" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="mt-1">
                                        <small id="password-strength-text" class="text-muted">Enter a new password to see its strength</small>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-lock me-2"></i> Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Security Settings Tab -->
                        <div class="tab-pane fade" id="security-settings" role="tabpanel" aria-labelledby="security-tab">
                            <div class="mt-2">
                                <h4 class="h5 mb-3">Security Settings</h4>
                                
                                <!-- Email Verification Status -->
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="h6 mb-1">Email Verification</h5>
                                                <p class="text-muted mb-0 small">Verify your email address to secure your account</p>
                                            </div>
                                            <div>
                                                @if($user->hasVerifiedEmail())
                                                    <span class="badge bg-success">Verified</span>
                                                @else
                                                    <form method="POST" action="{{ route('verification.send') }}">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-envelope me-1"></i> Send Verification Email
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Account Deletion -->
                                <div class="card mt-4 border-danger">
                                    <div class="card-header bg-danger text-white">
                                        <h5 class="card-title mb-0 h6">Delete Account</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">Once your account is deleted, all of your data will be permanently removed. Before deleting your account, please download any data or information that you wish to retain.</p>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                            <i class="fas fa-trash-alt me-2"></i> Delete Account
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteAccountModalLabel">Delete Account</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete your account? This action cannot be undone and all your data will be permanently deleted.</p>
                <form method="POST" action="{{ route('profile.destroy') }}" id="delete-account-form">
                    @csrf
                    @method('DELETE')
                    
                    <div class="mb-3">
                        <label for="delete-password" class="form-label">Password</label>
                        <input id="delete-password" type="password" class="form-control" name="password" 
                               placeholder="Enter your password to confirm" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('delete-account-form').submit();">
                    <i class="fas fa-trash-alt me-2"></i> Delete Account
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password visibility toggle
        const toggleButtons = document.querySelectorAll('.password-toggle');
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const targetInput = document.getElementById(targetId);
                const icon = this.querySelector('i');
                
                if (targetInput.type === 'password') {
                    targetInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    targetInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
        
        // Password strength meter
        const passwordInput = document.getElementById('password');
        const strengthMeter = document.getElementById('password-strength-meter');
        const strengthText = document.getElementById('password-strength-text');
        
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                let feedback = '';
                
                if (password.length >= 8) {
                    strength += 25;
                }
                
                if (password.match(/[a-z]/) && password.match(/[A-Z]/)) {
                    strength += 25;
                }
                
                if (password.match(/\d/)) {
                    strength += 25;
                }
                
                if (password.match(/[^a-zA-Z\d]/)) {
                    strength += 25;
                }
                
                // Update strength meter
                strengthMeter.style.width = strength + '%';
                
                if (strength === 0) {
                    strengthMeter.className = 'progress-bar';
                    strengthText.textContent = 'Enter a new password to see its strength';
                } else if (strength <= 25) {
                    strengthMeter.className = 'progress-bar bg-danger';
                    feedback = 'Very weak';
                } else if (strength <= 50) {
                    strengthMeter.className = 'progress-bar bg-warning';
                    feedback = 'Weak';
                } else if (strength <= 75) {
                    strengthMeter.className = 'progress-bar bg-info';
                    feedback = 'Moderate';
                } else {
                    strengthMeter.className = 'progress-bar bg-success';
                    feedback = 'Strong';
                }
                
                if (strength > 0) {
                    strengthText.textContent = 'Password strength: ' + feedback;
                }
            });
        }
        
        // Open specific tab based on URL hash
        const hash = window.location.hash;
        if (hash) {
            const tabId = hash.substring(1); // Remove the # character
            const tabElement = document.getElementById(tabId);
            if (tabElement) {
                const tab = new bootstrap.Tab(document.querySelector(`[data-bs-target="#${tabId}"]`));
                tab.show();
            }
        }
    });
</script>
@endpush
@endsection