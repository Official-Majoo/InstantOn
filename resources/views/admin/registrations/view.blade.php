@extends('layouts.admin')

@section('title', 'Customer Profile')
@section('page_title', 'Customer Profile')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.registrations') }}">Registrations</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $customerProfile->full_name }}</li>
@endsection

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Customer Profile</h2>
        <p class="text-muted">View and manage customer registration details.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.registrations') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-cog me-2"></i> Actions
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                @if($customerProfile->verification_status === 'pending')
                <li><button type="button" class="dropdown-item text-success" data-bs-toggle="modal" data-bs-target="#verifyCustomerModal">
                    <i class="fas fa-check-circle me-2"></i> Verify Customer
                </button></li>
                <li><button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#rejectCustomerModal">
                    <i class="fas fa-times-circle me-2"></i> Reject Customer
                </button></li>
                @else
                <li><button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#resetStatusModal">
                    <i class="fas fa-redo me-2"></i> Reset Status
                </button></li>
                @endif
                <li><a class="dropdown-item" href="#">
                    <i class="fas fa-envelope me-2"></i> Send Email
                </a></li>
                <li><a class="dropdown-item" href="#">
                    <i class="fas fa-comment-alt me-2"></i> Send SMS
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#" id="printProfile">
                    <i class="fas fa-print me-2"></i> Print Profile
                </a></li>
                <li><a class="dropdown-item" href="#" id="exportPDF">
                    <i class="fas fa-file-pdf me-2"></i> Export as PDF
                </a></li>
            </ul>
        </div>
    </div>
</div>

<!-- Customer Profile Overview -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-8">
                <div class="d-flex">
                    <div class="avatar me-3" style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ substr($customerProfile->first_name, 0, 1) }}{{ substr($customerProfile->last_name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">{{ $customerProfile->full_name }}</h3>
                        <div class="d-flex flex-wrap gap-3 mb-2">
                            <div>
                                <span class="text-muted">Omang:</span>
                                <span class="fw-medium">{{ $customerProfile->omang_number }}</span>
                            </div>
                            <div>
                                <span class="text-muted">Phone:</span>
                                <span class="fw-medium">{{ $customerProfile->user->phone_number }}</span>
                            </div>
                            <div>
                                <span class="text-muted">Email:</span>
                                <span class="fw-medium">{{ $customerProfile->user->email }}</span>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="status-indicator {{ $customerProfile->verification_status === 'verified' ? 'status-verified' : ($customerProfile->verification_status === 'rejected' ? 'status-rejected' : 'status-pending') }}">
                                <i class="fas fa-{{ $customerProfile->verification_status === 'verified' ? 'check-circle' : ($customerProfile->verification_status === 'rejected' ? 'times-circle' : 'clock') }}"></i>
                                {{ ucfirst($customerProfile->verification_status) }}
                            </span>
                            <span class="status-indicator status-{{ $customerProfile->user->status === 'active' ? 'verified' : ($customerProfile->user->status === 'rejected' ? 'rejected' : 'pending') }}">
                                <i class="fas fa-{{ $customerProfile->user->status === 'active' ? 'check-circle' : ($customerProfile->user->status === 'rejected' ? 'times-circle' : 'clock') }}"></i>
                                Account {{ ucfirst($customerProfile->user->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="d-flex flex-column h-100 justify-content-center">
                    <div class="mb-3 text-lg-end">
                        <span class="text-muted">Registered:</span>
                        <span class="fw-medium">{{ $customerProfile->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    @if($customerProfile->verification_status === 'pending')
                    <div class="d-flex gap-2 justify-content-lg-end">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#verifyCustomerModal">
                            <i class="fas fa-check-circle me-2"></i> Verify
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectCustomerModal">
                            <i class="fas fa-times-circle me-2"></i> Reject
                        </button>
                    </div>
                    @else
                    <div class="d-flex flex-column text-lg-end">
                        <div class="mb-1">
                            <span class="text-muted">Last Updated:</span>
                            <span class="fw-medium">{{ $customerProfile->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-muted">Updated By:</span>
                            <span class="fw-medium">{{ $customerProfile->reviews->last()?->officer->name ?? 'System' }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customer Information Tabs -->
<div class="card mb-4">
    <div class="card-header p-0">
        <ul class="nav nav-tabs card-header-tabs" id="customerProfileTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab" aria-controls="personal" aria-selected="true">
                    <i class="fas fa-user me-2"></i> Personal Information
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab" aria-controls="documents" aria-selected="false">
                    <i class="fas fa-file-alt me-2"></i> Documents <span class="badge bg-primary rounded-pill ms-1">{{ $customerProfile->documents->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="verification-tab" data-bs-toggle="tab" data-bs-target="#verification" type="button" role="tab" aria-controls="verification" aria-selected="false">
                    <i class="fas fa-shield-alt me-2"></i> Verification
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab" aria-controls="activity" aria-selected="false">
                    <i class="fas fa-history me-2"></i> Activity <span class="badge bg-primary rounded-pill ms-1">{{ $activities->count() }}</span>
                </button>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="customerProfileTabContent">
            <!-- Personal Information Tab -->
            <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header py-3">
                                <h5 class="card-title mb-0">Basic Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-4 text-muted">Full Name</div>
                                    <div class="col-md-8 fw-medium">{{ $customerProfile->full_name }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 text-muted">Omang Number</div>
                                    <div class="col-md-8 fw-medium">{{ $customerProfile->omang_number }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 text-muted">Date of Birth</div>
                                    <div class="col-md-8 fw-medium">{{ $customerProfile->date_of_birth->format('d M Y') }} ({{ $customerProfile->date_of_birth->age }} years)</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 text-muted">Gender</div>
                                    <div class="col-md-8 fw-medium">{{ ucfirst($customerProfile->gender) }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 text-muted">Nationality</div>
                                    <div class="col-md-8 fw-medium">{{ $customerProfile->nationality ?? 'Botswana' }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 text-muted">Marital Status</div>
                                    <div class="col-md-8 fw-medium">{{ ucfirst($customerProfile->marital_status ?? 'Not specified') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header py-3">
                                <h5 class="card-title mb-0">Contact Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-4 text-muted">Email</div>
                                    <div class="col-md-8 fw-medium">{{ $customerProfile->user->email }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 text-muted">Phone Number</div>
                                    <div class="col-md-8 fw-medium">{{ $customerProfile->user->phone_number }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 text-muted">Physical Address</div>
                                    <div class="col-md-8 fw-medium">{{ $customerProfile->physical_address ?? 'Not provided' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 text-muted">Postal Address</div>
                                    <div class="col-md-8 fw-medium">{{ $customerProfile->postal_address ?? 'Not provided' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 text-muted">City/Town</div>
                                    <div class="col-md-8 fw-medium">{{ $customerProfile->city ?? 'Not provided' }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 text-muted">Employment</div>
                                    <div class="col-md-8 fw-medium">{{ $customerProfile->employment_status ?? 'Not provided' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header py-3">
                                <h5 class="card-title mb-0">Additional Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <div class="text-muted mb-1">Account Status</div>
                                            <div class="fw-medium">{{ ucfirst($customerProfile->user->status) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <div class="text-muted mb-1">Registration Date</div>
                                            <div class="fw-medium">{{ $customerProfile->created_at->format('d M Y, H:i') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <div class="text-muted mb-1">Last Login</div>
                                            <div class="fw-medium">{{ $customerProfile->user->last_login_at ? $customerProfile->user->last_login_at->format('d M Y, H:i') : 'Never' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <div class="text-muted mb-1">IP Address</div>
                                            <div class="fw-medium">{{ $customerProfile->user->last_login_ip ?? 'Not available' }}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($customerProfile->additional_notes)
                                <div class="mt-3">
                                    <div class="text-muted mb-1">Additional Notes</div>
                                    <div class="fw-medium">{{ $customerProfile->additional_notes }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Documents Tab -->
            <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Uploaded Documents</h5>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                        <i class="fas fa-upload me-2"></i> Upload Document
                    </button>
                </div>
                
                <div class="row g-4">
                    @forelse($customerProfile->documents as $document)
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-circle icon-primary me-3" style="width: 48px; height: 48px;">
                                        <i class="fas fa-{{ 
                                            str_contains(strtolower($document->document_type), 'omang') ? 'id-card' : 
                                            (str_contains(strtolower($document->document_type), 'passport') ? 'passport' : 
                                            (str_contains(strtolower($document->document_type), 'selfie') ? 'camera' : 
                                            (str_contains(strtolower($document->document_type), 'proof') ? 'file-invoice' : 'file-alt'))) 
                                        }}"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $document->document_type }}</h6>
                                        <p class="text-muted mb-0 small">
                                            {{ $document->created_at->format('d M Y, H:i') }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="document-preview mb-3">
                                    @if(in_array(strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                                        <img src="{{ asset('storage/' . $document->file_path) }}" class="img-fluid rounded" alt="{{ $document->document_type }}">
                                    @else
                                        <div class="document-file-icon text-center py-4">
                                            <i class="fas fa-file-{{ 
                                                in_array(strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION)), ['pdf']) ? 'pdf' : 
                                                (in_array(strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION)), ['doc', 'docx']) ? 'word' : 
                                                (in_array(strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION)), ['xls', 'xlsx']) ? 'excel' : 'alt')) 
                                            }} fa-4x text-muted"></i>
                                            <p class="mt-2 mb-0 text-muted small">{{ strtoupper(pathinfo($document->file_path, PATHINFO_EXTENSION)) }} File</p>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <a href="{{ asset('storage/' . $document->file_path) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="fas fa-eye me-2"></i> View Document
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteDocumentModal{{ $document->id }}">
                                        <i class="fas fa-trash-alt me-2"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <div class="icon-circle icon-secondary mx-auto mb-4" style="width: 80px; height: 80px; font-size: 2rem;">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h5>No Documents Found</h5>
                            <p class="text-muted">This customer has not uploaded any documents yet.</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                                <i class="fas fa-upload me-2"></i> Upload Document
                            </button>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Verification Tab -->
            <div class="tab-pane fade" id="verification" role="tabpanel" aria-labelledby="verification-tab">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="card h-100">
                            <div class="card-header py-3">
                                <h5 class="card-title mb-0">Verification Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="verification-status-card p-4 text-center">
                                    <div class="icon-circle mx-auto mb-3" 
                                        style="width: 80px; height: 80px; font-size: 2rem; 
                                        background-color: {{ $customerProfile->verification_status === 'verified' ? '#DCFCE7' : ($customerProfile->verification_status === 'rejected' ? '#FEE2E2' : '#FFF8E6') }};
                                        color: {{ $customerProfile->verification_status === 'verified' ? '#0CAA68' : ($customerProfile->verification_status === 'rejected' ? '#E94E4D' : '#FFC336') }};">
                                        <i class="fas fa-{{ $customerProfile->verification_status === 'verified' ? 'check-circle' : ($customerProfile->verification_status === 'rejected' ? 'times-circle' : 'clock') }}"></i>
                                    </div>
                                    
                                    <h4 class="mb-2">{{ ucfirst($customerProfile->verification_status) }}</h4>
                                    <p class="text-muted mb-4">
                                        @if($customerProfile->verification_status === 'verified')
                                            This customer has been verified and can access all FNBB services.
                                        @elseif($customerProfile->verification_status === 'rejected')
                                            This customer's registration has been rejected.
                                        @else
                                            This customer is awaiting verification review.
                                        @endif
                                    </p>
                                    
                                    @if($customerProfile->verification_status === 'rejected')
                                    <div class="alert alert-danger">
                                        <h6 class="alert-heading">Rejection Reason:</h6>
                                        <p class="mb-0">{{ $customerProfile->rejection_reason }}</p>
                                    </div>
                                    @endif
                                    
                                    @if($customerProfile->verification_status === 'pending')
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#verifyCustomerModal">
                                            <i class="fas fa-check-circle me-2"></i> Verify
                                        </button>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectCustomerModal">
                                            <i class="fas fa-times-circle me-2"></i> Reject
                                        </button>
                                    </div>
                                    @else
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#resetStatusModal">
                                        <i class="fas fa-redo me-2"></i> Reset Status
                                    </button>
                                    @endif
                                </div>
                                
                                @if($customerProfile->reviews->isNotEmpty())
                                <div class="mt-4">
                                    <h6 class="mb-3">Review History</h6>
                                    <div class="timeline">
                                        @foreach($customerProfile->reviews->sortByDesc('review_timestamp') as $review)
                                        <div class="timeline-item">
                                            <div class="timeline-icon {{ $review->status === 'verified' ? 'success' : ($review->status === 'rejected' ? 'danger' : 'warning') }}"></div>
                                            <div class="timeline-content">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="fw-medium">{{ $review->officer->name }}</span>
                                                    <span class="timeline-time">{{ $review->review_timestamp->format('d M Y, H:i') }}</span>
                                                </div>
                                                <p class="mb-0">
                                                    Status changed to <span class="fw-medium">{{ ucfirst($review->status) }}</span>
                                                </p>
                                                @if($review->notes)
                                                <div class="small text-muted mt-1">
                                                    "{{ $review->notes }}"
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="card h-100">
                            <div class="card-header py-3">
                                <h5 class="card-title mb-0">Facial Verification</h5>
                            </div>
                            <div class="card-body">
                                @if($customerProfile->verificationSessions->isNotEmpty())
                                    @php
                                        $verificationSession = $customerProfile->verificationSessions->sortByDesc('created_at')->first();
                                        $similarityScore = $verificationSession->similarity_score ?? 0;
                                        $passed = $similarityScore >= 80;
                                    @endphp
                                    
                                    <div class="text-center mb-4">
                                        <div class="icon-circle mx-auto mb-3" 
                                            style="width: 80px; height: 80px; font-size: 2rem; 
                                            background-color: {{ $passed ? '#DCFCE7' : '#FEE2E2' }};
                                            color: {{ $passed ? '#0CAA68' : '#E94E4D' }};">
                                            <i class="fas fa-{{ $passed ? 'check-circle' : 'times-circle' }}"></i>
                                        </div>
                                        
                                        <h4 class="mb-2">{{ $passed ? 'Passed' : 'Failed' }}</h4>
                                        <p class="text-muted mb-4">
                                            {{ $passed ? 'The facial verification check was successful.' : 'The facial verification check was unsuccessful.' }}
                                        </p>
                                    </div>
                                    
                                    <div class="card mb-4">
                                        <div class="card-header py-2 bg-light">
                                            <h6 class="card-title mb-0">Similarity Score</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center mb-2">
                                                <span class="display-4 fw-bold {{ $passed ? 'text-success' : 'text-danger' }}">{{ number_format($similarityScore, 1) }}%</span>
                                            </div>
                                            <div class="progress" style="height: 10px;">
                                                <div class="progress-bar bg-{{ $similarityScore >= 80 ? 'success' : ($similarityScore >= 60 ? 'warning' : 'danger') }}" 
                                                    role="progressbar" 
                                                    style="width: {{ $similarityScore }}%;" 
                                                    aria-valuenow="{{ $similarityScore }}" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100"></div>
                                            </div>
                                            <div class="d-flex justify-content-between mt-1">
                                                <span class="small">0%</span>
                                                <span class="small">50%</span>
                                                <span class="small">100%</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header py-2 bg-light">
                                                    <h6 class="card-title mb-0">ID Photo</h6>
                                                </div>
                                                <div class="card-body p-2">
                                                    <img src="{{ asset('storage/' . ($verificationSession->id_photo_path ?? 'placeholder.jpg')) }}" class="img-fluid rounded" alt="ID Photo">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header py-2 bg-light">
                                                    <h6 class="card-title mb-0">Selfie Photo</h6>
                                                </div>
                                                <div class="card-body p-2">
                                                    <img src="{{ asset('storage/' . ($verificationSession->selfie_photo_path ?? 'placeholder.jpg')) }}" class="img-fluid rounded" alt="Selfie Photo">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <div class="row mb-2">
                                            <div class="col-md-4 text-muted">Session ID</div>
                                            <div class="col-md-8 fw-medium">{{ $verificationSession->session_id }}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-4 text-muted">Verification Date</div>
                                            <div class="col-md-8 fw-medium">{{ $verificationSession->created_at->format('d M Y, H:i') }}</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 text-muted">IP Address</div>
                                            <div class="col-md-8 fw-medium">{{ $verificationSession->ip_address ?? 'Not recorded' }}</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <div class="icon-circle icon-secondary mx-auto mb-4" style="width: 80px; height: 80px; font-size: 2rem;">
                                            <i class="fas fa-camera"></i>
                                        </div>
                                        <h5>No Facial Verification Data</h5>
                                        <p class="text-muted">This customer has not completed the facial verification process yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Activity Tab -->
            <div class="tab-pane fade" id="activity" role="tabpanel" aria-labelledby="activity-tab">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Activity Log</h5>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="refreshActivity">
                            <i class="fas fa-sync-alt me-2"></i> Refresh
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary ms-2" id="exportActivity">
                            <i class="fas fa-download me-2"></i> Export
                        </button>
                    </div>
                </div>
                
                <div class="timeline">
                    @forelse($activities as $activity)
                    <div class="timeline-item">
                        <div class="timeline-icon {{ 
                            str_contains($activity->log_name, 'verification') ? 'success' : 
                            (str_contains($activity->log_name, 'login') ? 'info' : 
                            (str_contains($activity->log_name, 'reject') ? 'danger' : 'primary')) 
                        }}"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-medium">{{ $activity->causer->name ?? 'System' }}</span>
                                <span class="timeline-time">{{ $activity->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            <p class="mb-1">{{ $activity->description }}</p>
                            @if($activity->properties && count($activity->properties) > 0)
                            <div class="small text-muted">
                                @foreach($activity->properties as $key => $value)
                                    @if(!in_array($key, ['id', 'timestamp']) && !is_array($value))
                                        <span class="me-3">{{ ucfirst($key) }}: {{ is_bool($value) ? ($value ? 'Yes' : 'No') : $value }}</span>
                                    @endif
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <div class="icon-circle icon-secondary mx-auto mb-4" style="width: 80px; height: 80px; font-size: 2rem;">
                            <i class="fas fa-history"></i>
                        </div>
                        <h5>No Activity Found</h5>
                        <p class="text-muted">There is no recorded activity for this customer yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Verify Customer Modal -->
<div class="modal fade" id="verifyCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verify Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.customer.status', $customerProfile->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="verification_status" value="verified">
                
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem; background-color: #DCFCE7; color: #0CAA68;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h5>Verify Customer Registration</h5>
                        <p class="text-muted">You are about to verify this customer's registration. They will be granted access to use FNBB services.</p>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notesVerify" class="form-label">Verification Notes (Optional)</label>
                        <textarea class="form-control" id="notesVerify" name="notes" rows="3" placeholder="Add any notes about this verification..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Verify Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Customer Modal -->
<div class="modal fade" id="rejectCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.customer.status', $customerProfile->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="verification_status" value="rejected">
                
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem; background-color: #FEE2E2; color: #E94E4D;">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <h5>Reject Customer Registration</h5>
                        <p class="text-muted">You are about to reject this customer's registration. They will be notified of the rejection.</p>
                    </div>
                    
                    <div class="mb-3">
                        <label for="rejectionReason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejectionReason" name="rejection_reason" rows="3" placeholder="Please provide the reason for rejection..." required></textarea>
                        <div class="form-text">This reason will be communicated to the customer.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Registration</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reset Status Modal -->
<div class="modal fade" id="resetStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reset Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.customer.status', $customerProfile->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="verification_status" value="pending">
                
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem; background-color: #FFF8E6; color: #FFC336;">
                            <i class="fas fa-redo"></i>
                        </div>
                        <h5>Reset Verification Status</h5>
                        <p class="text-muted">You are about to reset this customer's verification status to pending. This will require re-verification.</p>
                    </div>
                    
                    <div class="mb-3">
                        <label for="resetReason" class="form-label">Reset Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="resetReason" name="notes" rows="3" placeholder="Please provide the reason for resetting the status..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Reset Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="customer_profile_id" value="{{ $customerProfile->id }}">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="document_type" class="form-label">Document Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="document_type" name="document_type" required>
                            <option value="" selected disabled>Select document type</option>
                            <option value="Omang ID">Omang ID</option>
                            <option value="Passport">Passport</option>
                            <option value="Proof of Address">Proof of Address</option>
                            <option value="Proof of Income">Proof of Income</option>
                            <option value="Selfie Photo">Selfie Photo</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="document_file" class="form-label">Document File <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="document_file" name="document_file" required>
                        <div class="form-text">Accepted file types: PDF, JPG, PNG, DOC, DOCX (max 5MB)</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="document_notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="document_notes" name="document_notes" rows="2" placeholder="Add any notes about this document..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload Document</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Document Delete Modals -->
@foreach($customerProfile->documents as $document)
<div class="modal fade" id="deleteDocumentModal{{ $document->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem; background-color: #FEE2E2; color: #E94E4D;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h5>Delete Document</h5>
                    <p class="text-muted">Are you sure you want to delete this document? This action cannot be undone.</p>
                </div>
                
                <div class="border rounded p-3 mb-3 bg-light">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle icon-primary me-3" style="width: 40px; height: 40px;">
                            <i class="fas fa-{{ 
                                str_contains(strtolower($document->document_type), 'omang') ? 'id-card' : 
                                (str_contains(strtolower($document->document_type), 'passport') ? 'passport' : 
                                (str_contains(strtolower($document->document_type), 'selfie') ? 'camera' : 
                                (str_contains(strtolower($document->document_type), 'proof') ? 'file-invoice' : 'file-alt'))) 
                            }}"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $document->document_type }}</h6>
                            <p class="text-muted mb-0 small">
                                {{ $document->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="#" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Document</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Print profile functionality
    document.getElementById('printProfile')?.addEventListener('click', function(e) {
        e.preventDefault();
        window.print();
    });
    
    // Export PDF functionality
    document.getElementById('exportPDF')?.addEventListener('click', function(e) {
        e.preventDefault();
        // Implement PDF export logic
        alert('Export as PDF functionality will be implemented here');
    });
    
    // Refresh activity functionality
    document.getElementById('refreshActivity')?.addEventListener('click', function(e) {
        e.preventDefault();
        window.location.reload();
    });
    
    // Export activity functionality
    document.getElementById('exportActivity')?.addEventListener('click', function(e) {
        e.preventDefault();
        // Implement activity export logic
        alert('Export activity functionality will be implemented here');
    });
    
    // Tab memory using URL hash
    const hash = window.location.hash;
    if (hash) {
        const tab = document.querySelector(`#customerProfileTabs button[data-bs-target="${hash}"]`);
        if (tab) {
            new bootstrap.Tab(tab).show();
        }
    }
    
    // Update URL hash when tabs change
    const tabEls = document.querySelectorAll('#customerProfileTabs button[data-bs-toggle="tab"]');
    tabEls.forEach(tabEl => {
        tabEl.addEventListener('shown.bs.tab', function (event) {
            window.location.hash = event.target.dataset.bsTarget;
        });
    });
});
</script>
@endpush

@push('styles')
<style>
    @media print {
        .sidebar, .topbar, .app-footer, .card-header, .card-footer, .btn, button, 
        .nav-tabs, .modal, [data-bs-toggle], [data-bs-target], .dropdown-toggle {
            display: none !important;
        }
        
        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        
        .card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            break-inside: avoid;
        }
        
        .content-wrapper {
            padding: 0 !important;
        }
        
        .tab-content > .tab-pane {
            display: block !important;
            opacity: 1 !important;
            break-inside: avoid;
            margin-bottom: 2rem;
        }
        
        .tab-pane:not(:first-child) {
            border-top: 1px solid #ddd;
            padding-top: 2rem;
        }
    }
    
    .document-preview {
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background-color: #f8f9fa;
        border-radius: 0.375rem;
    }
    
    .document-preview img {
        max-height: 150px;
        object-fit: contain;
    }
</style>
@endpush
@endsection