<x-app-layout>
    <x-slot name="header">
        <h1 class="h3 mb-0 fw-bold">Home</h1>
    </x-slot>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="card-title fw-bold mb-4">Welcome to FNBB InstantOn</h2>
                    
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="fas fa-info-circle me-3 fs-4"></i>
                        <div>
                            <strong>Getting Started:</strong> Follow the registration wizard to complete your FNBB account setup.
                        </div>
                    </div>
                    
                    @if(auth()->user()->hasRole('customer'))
                        @php
                            $customer = auth()->user()->customerProfile;
                            $verificationStatus = $customer ? $customer->verification_status : 'not_started';
                        @endphp
                        
                        <div class="mt-4">
                            <h5 class="fw-bold mb-3">Your Registration Progress</h5>
                            
                            <div class="progress mb-3" style="height: 25px;">
                                @switch($verificationStatus)
                                    @case('not_started')
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                        @break
                                    @case('omang_verified')
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 33%;" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100">33%</div>
                                        @break
                                    @case('facial_verified')
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 66%;" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100">66%</div>
                                        @break
                                    @case('submitted')
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 90%;" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">90%</div>
                                        @break
                                    @case('approved')
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">100%</div>
                                        @break
                                    @default
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                @endswitch
                            </div>
                            
                            <div class="row g-4 mt-2">
                                <div class="col-md-3">
                                    <div class="card h-100 {{ $verificationStatus != 'not_started' ? 'border-success' : 'border-light' }}">
                                        <div class="card-body text-center p-3">
                                            <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center {{ $verificationStatus != 'not_started' ? 'bg-success' : 'bg-light' }}" style="width: 50px; height: 50px;">
                                                @if($verificationStatus != 'not_started')
                                                    <i class="fas fa-check text-white"></i>
                                                @else
                                                    <i class="fas fa-id-card text-muted"></i>
                                                @endif
                                            </div>
                                            <h6 class="fw-bold">Omang Verification</h6>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="card h-100 {{ in_array($verificationStatus, ['facial_verified', 'submitted', 'approved']) ? 'border-success' : 'border-light' }}">
                                        <div class="card-body text-center p-3">
                                            <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center {{ in_array($verificationStatus, ['facial_verified', 'submitted', 'approved']) ? 'bg-success' : 'bg-light' }}" style="width: 50px; height: 50px;">
                                                @if(in_array($verificationStatus, ['facial_verified', 'submitted', 'approved']))
                                                    <i class="fas fa-check text-white"></i>
                                                @else
                                                    <i class="fas fa-camera text-muted"></i>
                                                @endif
                                            </div>
                                            <h6 class="fw-bold">Facial Verification</h6>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="card h-100 {{ in_array($verificationStatus, ['submitted', 'approved']) ? 'border-success' : 'border-light' }}">
                                        <div class="card-body text-center p-3">
                                            <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center {{ in_array($verificationStatus, ['submitted', 'approved']) ? 'bg-success' : 'bg-light' }}" style="width: 50px; height: 50px;">
                                                @if(in_array($verificationStatus, ['submitted', 'approved']))
                                                    <i class="fas fa-check text-white"></i>
                                                @else
                                                    <i class="fas fa-file-alt text-muted"></i>
                                                @endif
                                            </div>
                                            <h6 class="fw-bold">Profile Completion</h6>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="card h-100 {{ $verificationStatus == 'approved' ? 'border-success' : 'border-light' }}">
                                        <div class="card-body text-center p-3">
                                            <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center {{ $verificationStatus == 'approved' ? 'bg-success' : 'bg-light' }}" style="width: 50px; height: 50px;">
                                                @if($verificationStatus == 'approved')
                                                    <i class="fas fa-check text-white"></i>
                                                @else
                                                    <i class="fas fa-user-check text-muted"></i>
                                                @endif
                                            </div>
                                            <h6 class="fw-bold">Officer Approval</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                                @if($verificationStatus == 'not_started')
                                    <a href="{{ route('registration.start') }}" class="btn btn-primary px-4">
                                        <i class="fas fa-play me-2"></i> Start Registration
                                    </a>
                                @elseif($verificationStatus == 'omang_verified')
                                    <a href="{{ route('registration.facial') }}" class="btn btn-primary px-4">
                                        <i class="fas fa-camera me-2"></i> Continue to Facial Verification
                                    </a>
                                @elseif($verificationStatus == 'facial_verified')
                                    <a href="{{ route('customer.profile.edit') }}" class="btn btn-primary px-4">
                                        <i class="fas fa-user-edit me-2"></i> Complete Your Profile
                                    </a>
                                @elseif($verificationStatus == 'submitted')
                                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                                        <i class="fas fa-clock me-3 fs-4"></i>
                                        <div>
                                            Your registration is under review by an FNBB officer. We'll notify you once it's approved.
                                        </div>
                                    </div>
                                @elseif($verificationStatus == 'approved')
                                    <div class="alert alert-success d-flex align-items-center" role="alert">
                                        <i class="fas fa-check-circle me-3 fs-4"></i>
                                        <div>
                                            <strong>Congratulations!</strong> Your FNBB registration has been approved. You can now access all FNBB services.
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    @if(auth()->user()->hasRole('bank-officer'))
                        <div class="mt-4">
                            <h5 class="fw-bold mb-3">Bank Officer Dashboard</h5>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 bg-primary bg-opacity-10">
                                        <div class="card-body p-4">
                                            <h2 class="display-4 fw-bold mb-0 text-primary">{{ App\Models\VerificationSession::where('status', 'submitted')->count() }}</h2>
                                            <p class="text-muted mb-3">Pending Registrations</p>
                                            <a href="{{ route('officer.queue') }}" class="btn btn-primary">
                                                <i class="fas fa-list-check me-2"></i> View Queue
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 bg-success bg-opacity-10">
                                        <div class="card-body p-4">
                                            <h2 class="display-4 fw-bold mb-0 text-success">{{ App\Models\VerificationSession::where('status', 'approved')->count() }}</h2>
                                            <p class="text-muted mb-3">Approved Registrations</p>
                                            <a href="{{ route('officer.reports') }}" class="btn btn-success">
                                                <i class="fas fa-chart-line me-2"></i> View Reports
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-3">Need Help?</h5>
                    <p class="text-muted mb-4">If you have any questions or encounter issues during the registration process, our support team is here to help.</p>
                    
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fas fa-life-ring me-2"></i> Contact Support
                        </a>
                        <a href="#" class="btn btn-outline-secondary">
                            <i class="fas fa-question-circle me-2"></i> FAQs
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-3">FNBB Updates</h5>
                    
                    <div class="mb-3 pb-3 border-bottom">
                        <span class="badge bg-primary mb-2">New</span>
                        <h6 class="fw-bold mb-1">Extended Banking Hours</h6>
                        <p class="small text-muted mb-0">We've extended our banking hours at selected branches to better serve you.</p>
                    </div>
                    
                    <div class="mb-3 pb-3 border-bottom">
                        <span class="badge bg-info mb-2">Service</span>
                        <h6 class="fw-bold mb-1">Mobile Banking Updates</h6>
                        <p class="small text-muted mb-0">Our mobile banking app has been updated with new features and improved security.</p>
                    </div>
                    
                    <div>
                        <span class="badge bg-success mb-2">Promotion</span>
                        <h6 class="fw-bold mb-1">No Fee Transfers</h6>
                        <p class="small text-muted mb-0">Enjoy zero fees on all transfers between FNBB accounts until July 31st.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>