@extends('layouts.admin')

@section('title', 'Create User')
@section('page_title', 'Create User')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Users</a></li>
<li class="breadcrumb-item active" aria-current="page">Create User</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Create New User</h2>
        <p class="text-muted">Add a new user to the system.</p>
    </div>
    <div>
        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Users
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title">User Details</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="form-text">Password must be at least 8 characters long.</div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="role" class="form-label">User Role <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="" selected disabled>Select user role</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="bank_officer" {{ old('role') === 'bank_officer' ? 'selected' : '' }}>Bank Officer</option>
                            <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>Customer</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info mt-3">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-info-circle fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="alert-heading">Important Information</h6>
                                <p class="mb-0">
                                    After creating a user, they will receive an email with login instructions. 
                                    Admin and Bank Officer accounts will be activated immediately. 
                                    Customer accounts will require verification before they can access the system.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 text-end">
                <button type="reset" class="btn btn-light me-2">Reset Form</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i> Create User
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    
    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    
    // Toggle confirm password visibility
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const passwordConfirmation = document.getElementById('password_confirmation');
    
    toggleConfirmPassword.addEventListener('click', function() {
        const type = passwordConfirmation.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirmation.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    
    // Password strength validation
    const passwordInput = document.getElementById('password');
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        
        // Length check
        if (password.length >= 8) {
            strength += 1;
        }
        
        // Contains uppercase
        if (password.match(/[A-Z]/)) {
            strength += 1;
        }
        
        // Contains lowercase
        if (password.match(/[a-z]/)) {
            strength += 1;
        }
        
        // Contains numbers
        if (password.match(/[0-9]/)) {
            strength += 1;
        }
        
        // Contains special characters
        if (password.match(/[^A-Za-z0-9]/)) {
            strength += 1;
        }
        
        // Update strength meter
        let feedbackText = '';
        let feedbackClass = '';
        
        if (password.length === 0) {
            feedbackText = '';
            feedbackClass = '';
        } else if (strength < 2) {
            feedbackText = 'Weak password';
            feedbackClass = 'text-danger';
        } else if (strength < 4) {
            feedbackText = 'Moderate password';
            feedbackClass = 'text-warning';
        } else {
            feedbackText = 'Strong password';
            feedbackClass = 'text-success';
        }
        
        // Find or create feedback element
        let feedbackEl = document.getElementById('password-strength-feedback');
        if (!feedbackEl) {
            feedbackEl = document.createElement('div');
            feedbackEl.id = 'password-strength-feedback';
            feedbackEl.style.fontSize = '0.875rem';
            feedbackEl.style.marginTop = '0.25rem';
            this.parentNode.appendChild(feedbackEl);
        }
        
        feedbackEl.textContent = feedbackText;
        feedbackEl.className = feedbackClass;
    });
});
</script>
@endpush
@endsection