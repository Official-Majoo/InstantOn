{{-- resources/views/admin/registrations/view.blade.php --}}
@extends('layouts.admin')

@section('title', 'Customer Details')

@section('breadcrumbs')
<a href="{{ route('admin.dashboard') }}">Dashboard</a> / 
<a href="{{ route('admin.registrations') }}">Registrations</a> / 
Customer Details
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            Customer Details: {{ $customerProfile->full_name }}
        </h1>
        <div>
            <a href="{{ route('admin.registrations') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            <div class="btn-group ms-2">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-cog"></i> Actions
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                        <i class="fas fa-edit fa-sm fa-fw me-2 text-muted"></i> Update Status
                    </button></li>
                    <li><a class="dropdown-item" href="mailto:{{ $customerProfile->user->email }}">
                        <i class="fas fa-envelope fa-sm fa-fw me-2 text-muted"></i> Email Customer
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><button class="dropdown-item text-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteCustomerModal">
                        <i class="fas fa-trash fa-sm fa-fw me-2"></i> Delete Customer
                    </button></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Status Banner -->
    @if($customerProfile->verification_status === 'verified')
        <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-check-circle me-3 fs-4"></i>
            <div>
                <h5 class="alert-heading mb-1">Verified Customer</h5>
                <p class="mb-0">This customer has been fully verified and their account is active.</p>
            </div>
        </div>
    @elseif($customerProfile->verification_status === 'rejected')
        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-times-circle me-3 fs-4"></i>
            <div>
                <h5 class="alert-heading mb-1">Rejected Customer</h5>
                <p class="mb-0">This customer's verification has been rejected. Reason: {{ $customerProfile->rejection_reason }}</p>
            </div>
        </div>
    @else
        <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-3 fs-4"></i>
            <div>
                <h5 class="alert-heading mb-1">Pending Verification</h5>
                <p class="mb-0">This customer is waiting for verification by a bank officer.</p>
            </div>
        </div>
    @endif

    <!-- Customer Information Sections -->
    <div class="row">
        <!-- Personal Information Section -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Personal Information</h6>
                    <span class="badge bg-primary">
                        Customer ID: {{ $customerProfile->id }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Full Name</div>
                        <div class="col-md-8">{{ $customerProfile->full_name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Omang Number</div>
                        <div class="col-md-8">{{ $customerProfile->omang_number }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Date of Birth</div>
                        <div class="col-md-8">{{ $customerProfile->date_of_birth->format('d F Y') }} ({{ $customerProfile->date_of_birth->age }} years)</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Gender</div>
                        <div class="col-md-8">{{ ucfirst($customerProfile->gender) }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Nationality</div>
                        <div class="col-md-8">{{ $customerProfile->nationality ?? 'Botswana' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Email</div>
                        <div class="col-md-8">
                            <a href="mailto:{{ $customerProfile->user->email }}">{{ $customerProfile->user->email }}</a>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Phone Number</div>
                        <div class="col-md-8">
                            <a href="tel:{{ $customerProfile->user->phone_number }}">{{ $customerProfile->user->phone_number }}</a>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Registration Date</div>
                        <div class="col-md-8">{{ $customerProfile->created_at->format('d F Y, h:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address & Employment Section -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Address & Employment</h6>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editAddressModal">
                        <i class="fas fa-pencil-alt"></i> Edit
                    </button>
                </div>
                <div class="card-body">
                    @if($customerProfile->address)
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Address</div>
                            <div class="col-md-8">{{ $customerProfile->address }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">City</div>
                            <div class="col-md-8">{{ $customerProfile->city }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">District</div>
                            <div class="col-md-8">{{ $customerProfile->district }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Postal Code</div>
                            <div class="col-md-8">{{ $customerProfile->postal_code }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Occupation</div>
                            <div class="col-md-8">{{ $customerProfile->occupation }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Employer</div>
                            <div class="col-md-8">{{ $customerProfile->employer ?? 'Not provided' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Income Range</div>
                            <div class="col-md-8">
                                @php
                                    $incomeRanges = [
                                        'below_5000' => 'Below P5,000',
                                        '5000_to_10000' => 'P5,000 - P10,000',
                                        '10001_to_25000' => 'P10,001 - P25,000',
                                        '25001_to_50000' => 'P25,001 - P50,000',
                                        'above_50000' => 'Above P50,000'
                                    ];
                                @endphp
                                {{ $incomeRanges[$customerProfile->income_range] ?? $customerProfile->income_range }}
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i> Additional information not yet provided by customer.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Uploaded Documents Section -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Uploaded Documents</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Document Type</th>
                                    <th>Upload Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customerProfile->documents as $document)
                                <tr>
                                    <td>
                                        @php
                                            $documentTypes = [
                                                'omang_front' => 'Omang (Front)',
                                                'omang_back' => 'Omang (Back)',
                                                'proof_of_address' => 'Proof of Address',
                                                'selfie' => 'Selfie Photo'
                                            ];
                                        @endphp
                                        {{ $documentTypes[$document->document_type] ?? ucfirst(str_replace('_', ' ', $document->document_type)) }}
                                    </td>
                                    <td>{{ $document->uploaded_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($document->verification_status === 'verified')
                                            <span class="badge bg-success">Verified</span>
                                        @elseif($document->verification_status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ $document->getUrl() }}" class="btn btn-sm btn-primary" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#documentModal{{ $document->id }}">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">No documents uploaded yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verification Sessions Section -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Facial Verification History</h6>
                </div>
                <div class="card-body">
                    @if($customerProfile->verificationSessions->count() > 0)
                        <div class="list-group">
                            @foreach($customerProfile->verificationSessions as $session)
                                <div class="list-group-item list-group-item-action flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Verification Session #{{ $session->id }}</h6>
                                        <small>{{ $session->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Similarity Score:</strong> {{ number_format($session->similarity_score, 1) }}%</p>
                                            <p class="mb-1"><strong>Status:</strong> 
                                                @if($session->status === 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($session->status === 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @endif
                                            </p>
                                            @if($session->reviewed_by)
                                                <p class="mb-1"><strong>Reviewed By:</strong> {{ optional($session->reviewer)->name ?? 'Unknown' }}</p>
                                                <p class="mb-1"><strong>Review Date:</strong> {{ $session->reviewed_at->format('d/m/Y H:i') }}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-end">
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#sessionModal{{ $session->id }}">
                                                    View Details
                                                </button>
                                            </div>
                                            <div class="text-end mt-2">
                                                <small class="text-muted">IP: {{ $session->ip_address }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i> No facial verification sessions recorded yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bank Officer Reviews Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Bank Officer Reviews</h6>
        </div>
        <div class="card-body">
            @if($customerProfile->reviews->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Officer</th>
                                <th>Status</th>
                                <th>Notes</th>
                                <th>Review Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customerProfile->reviews as $review)
                                <tr>
                                    <td>{{ $review->officer->name }}</td>
                                    <td>
                                        @if($review->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($review->status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending Additional Info</span>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($review->notes, 50) }}</td>
                                    <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $review->id }}">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i> No bank officer reviews recorded yet.
                </div>
            @endif
        </div>
    </div>

    <!-- Activity Timeline Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Activity Timeline</h6>
        </div>
        <div class="card-body">
            <div class="timeline">
                @forelse($activities as $activity)
                    <div class="timeline-item">
                        <div class="timeline-icon bg-{{ 
                            $activity->log_name === 'customer_review_submitted' ? 'warning' : 
                            ($activity->log_name === 'facial_verification_attempt' ? 'info' : 'primary') 
                        }}">
                            <i class="fas fa-{{ 
                                $activity->log_name === 'customer_review_submitted' ? 'clipboard-check' : 
                                ($activity->log_name === 'facial_verification_attempt' ? 'camera' : 'bell') 
                            }}"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="timeline-header">
                                {{ optional($activity->causer)->name ?? 'System' }} 
                                {{ $activity->description }}
                            </h6>
                            <p class="timeline-text">
                                @if($activity->log_name === 'customer_review_submitted')
                                    Customer profile was {{ $activity->properties['status'] ?? 'reviewed' }}. 
                                    @if(isset($activity->properties['notes']))
                                        <span class="text-muted small">Note: "{{ Str::limit($activity->properties['notes'], 100) }}"</span>
                                    @endif
                                @elseif($activity->log_name === 'facial_verification_attempt')
                                    Facial verification with similarity score of {{ $activity->properties['similarity_score'] ?? '0' }}%.
                                    ({{ $activity->properties['passed'] ? 'Passed' : 'Failed' }})
                                @endif
                            </p>
                            <div class="timeline-footer">
                                <span class="text-muted small">{{ $activity->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <p class="text-muted">No activity records found for this customer.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">Update Verification Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.customer.status', $customerProfile->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="verification_status" class="form-label">Verification Status</label>
                        <select class="form-select" id="verification_status" name="verification_status" required>
                            <option value="verified" {{ $customerProfile->verification_status === 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="pending" {{ $customerProfile->verification_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="rejected" {{ $customerProfile->verification_status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="mb-3" id="rejection_reason_field" style="{{ $customerProfile->verification_status !== 'rejected' ? 'display: none;' : '' }}">
                        <label for="rejection_reason" class="form-label">Rejection Reason</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3">{{ $customerProfile->rejection_reason }}</textarea>
                        <div class="form-text">Please provide a clear reason for rejecting this customer's verification.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Document Modals -->
@foreach($customerProfile->documents as $document)
<div class="modal fade" id="documentModal{{ $document->id }}" tabindex="-1" aria-labelledby="documentModalLabel{{ $document->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentModalLabel{{ $document->id }}">
                    @php
                        $documentTypes = [
                            'omang_front' => 'Omang (Front)',
                            'omang_back' => 'Omang (Back)',
                            'proof_of_address' => 'Proof of Address',
                            'selfie' => 'Selfie Photo'
                        ];
                    @endphp
                    {{ $documentTypes[$document->document_type] ?? ucfirst(str_replace('_', ' ', $document->document_type)) }} Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="document-image">
                            @if($document->isImage())
                                <img src="{{ $document->getUrl() }}" alt="{{ $document->document_type }}" class="img-fluid">
                            @else
                                <div class="text-center py-5 bg-light rounded">
                                    <i class="fas fa-file-pdf fa-5x text-danger mb-3"></i>
                                    <p>PDF Document</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Document Information</h6>
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <th>Document Type</th>
                                    <td>{{ $documentTypes[$document->document_type] ?? ucfirst(str_replace('_', ' ', $document->document_type)) }}</td>
                                </tr>
                                <tr>
                                    <th>Upload Date</th>
                                    <td>{{ $document->uploaded_at->format('d F Y, h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>File Type</th>
                                    <td>{{ $document->mime_type }}</td>
                                </tr>
                                <tr>
                                    <th>File Size</th>
                                    <td>{{ number_format($document->file_size / 1024, 2) }} KB</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($document->verification_status === 'verified')
                                            <span class="badge bg-success">Verified</span>
                                        @elseif($document->verification_status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($document->verification_status === 'rejected')
                                <tr>
                                    <th>Rejection Reason</th>
                                    <td>{{ $document->rejection_reason }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ $document->getUrl() }}" class="btn btn-primary" download>Download Document</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Verification Session Modals -->
@foreach($customerProfile->verificationSessions as $session)
<div class="modal fade" id="sessionModal{{ $session->id }}" tabindex="-1" aria-labelledby="sessionModalLabel{{ $session->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sessionModalLabel{{ $session->id }}">Verification Session Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Selfie Photo</h6>
                        <div class="document-image">
                            @if($session->selfie_photo_path)
                                <img src="{{ Storage::url($session->selfie_photo_path) }}" alt="Selfie" class="img-fluid rounded">
                            @else
                                <div class="text-center py-5 bg-light rounded">
                                    <i class="fas fa-camera fa-5x text-muted mb-3"></i>
                                    <p>No selfie image available</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Omang Photo</h6>
                        <div class="document-image">
                            @if($session->omang_photo_path)
                                <img src="{{ Storage::url($session->omang_photo_path) }}" alt="Omang Photo" class="img-fluid rounded">
                            @else
                                <div class="text-center py-5 bg-light rounded">
                                    <i class="fas fa-id-card fa-5x text-muted mb-3"></i>
                                    <p>No Omang photo available</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Verification Details</h6>
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <th>Session ID</th>
                                    <td>{{ $session->id }}</td>
                                </tr>
                                <tr>
                                    <th>Date & Time</th>
                                    <td>{{ $session->created_at->format('d F Y, h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Similarity Score</th>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-{{ $session->similarity_score >= 70 ? 'success' : 'danger' }}" 
                                                role="progressbar" 
                                                style="width: {{ min($session->similarity_score, 100) }}%;" 
                                                aria-valuenow="{{ $session->similarity_score }}" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                                {{ number_format($session->similarity_score, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($session->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($session->status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Session Information</h6>
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <th>IP Address</th>
                                    <td>{{ $session->ip_address }}</td>
                                </tr>
                                <tr>
                                    <th>User Agent</th>
                                    <td class="text-truncate" style="max-width: 250px;" title="{{ $session->user_agent }}">
                                        {{ $session->user_agent }}
                                    </td>
                                </tr>
                                @if($session->device_details)
                                <tr>
                                    <th>Device</th>
                                    <td>
                                        @php
                                            $device = json_decode($session->device_details, true);
                                        @endphp
                                        {{ $device['platform'] ?? 'Unknown' }} 
                                        ({{ $device['mobile'] === 'true' ? 'Mobile' : 'Desktop' }})
                                    </td>
                                </tr>
                                @endif
                                @if($session->reviewed_by)
                                <tr>
                                    <th>Reviewed By</th>
                                    <td>{{ optional($session->reviewer)->name ?? 'Unknown' }}</td>
                                </tr>
                                <tr>
                                    <th>Review Date</th>
                                    <td>{{ $session->reviewed_at->format('d F Y, h:i A') }}</td>
                                </tr>
                                @endif
                                @if($session->notes)
                                <tr>
                                    <th>Notes</th>
                                    <td>{{ $session->notes }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                @if($session->status === 'pending')
                <div class="btn-group ms-2">
                    <form action="{{ route('admin.verification.approve', $session->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success">Approve</button>
                    </form>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $session->id }}">
                        Reject
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Reject Session Modal -->
@if($session->status === 'pending')
<div class="modal fade" id="rejectModal{{ $session->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $session->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel{{ $session->id }}">Reject Verification Session</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.verification.reject', $session->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_notes" class="form-label">Rejection Reason</label>
                        <textarea class="form-control" id="rejection_notes" name="notes" rows="3" required></textarea>
                        <div class="form-text">Please provide a clear reason for rejecting this verification session.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Verification</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endforeach

<!-- Review Modals -->
@foreach($customerProfile->reviews as $review)
<div class="modal fade" id="reviewModal{{ $review->id }}" tabindex="-1" aria-labelledby="reviewModalLabel{{ $review->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel{{ $review->id }}">Bank Officer Review Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h6 class="fw-bold">Officer Information</h6>
                    <p><strong>Name:</strong> {{ $review->officer->name }}</p>
                    <p><strong>Email:</strong> {{ $review->officer->email }}</p>
                    <p><strong>Review Date:</strong> {{ $review->created_at->format('d F Y, h:i A') }}</p>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold">Review Status</h6>
                    <p>
                        @if($review->status === 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($review->status === 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending Additional Info</span>
                        @endif
                    </p>
                </div>
                <div class="mb-3">
                    <h6 class="fw-bold">Notes</h6>
                    <div class="p-3 bg-light rounded">
                        {{ $review->notes }}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Delete Customer Modal -->
<div class="modal fade" id="deleteCustomerModal" tabindex="-1" aria-labelledby="deleteCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteCustomerModalLabel">Delete Customer</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning!</strong> This action cannot be undone. All customer data, documents, and verification sessions will be permanently deleted.
                </div>
                <p>Are you sure you want to delete this customer?</p>
                <div class="mb-3">
                    <strong>Customer:</strong> {{ $customerProfile->full_name }}
                </div>
                <div class="mb-3">
                    <strong>Omang Number:</strong> {{ $customerProfile->omang_number }}
                </div>
                <div class="mb-3">
                    <strong>Email:</strong> {{ $customerProfile->user->email }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.customer.delete', $customerProfile->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Delete Customer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Address & Employment Modal -->
<div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAddressModalLabel">Edit Address & Employment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.customer.update-address', $customerProfile->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ $customerProfile->address }}">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ $customerProfile->city }}">
                        </div>
                        <div class="col-md-6">
                            <label for="district" class="form-label">District</label>
                            <input type="text" class="form-control" id="district" name="district" value="{{ $customerProfile->district }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="postal_code" class="form-label">Postal Code</label>
                        <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ $customerProfile->postal_code }}">
                    </div>
                    <div class="mb-3">
                        <label for="occupation" class="form-label">Occupation</label>
                        <input type="text" class="form-control" id="occupation" name="occupation" value="{{ $customerProfile->occupation }}">
                    </div>
                    <div class="mb-3">
                        <label for="employer" class="form-label">Employer</label>
                        <input type="text" class="form-control" id="employer" name="employer" value="{{ $customerProfile->employer }}">
                    </div>
                    <div class="mb-3">
                        <label for="income_range" class="form-label">Income Range</label>
                        <select class="form-select" id="income_range" name="income_range">
                            <option value="below_5000" {{ $customerProfile->income_range === 'below_5000' ? 'selected' : '' }}>Below P5,000</option>
                            <option value="5000_to_10000" {{ $customerProfile->income_range === '5000_to_10000' ? 'selected' : '' }}>P5,000 - P10,000</option>
                            <option value="10001_to_25000" {{ $customerProfile->income_range === '10001_to_25000' ? 'selected' : '' }}>P10,001 - P25,000</option>
                            <option value="25001_to_50000" {{ $customerProfile->income_range === '25001_to_50000' ? 'selected' : '' }}>P25,001 - P50,000</option>
                            <option value="above_50000" {{ $customerProfile->income_range === 'above_50000' ? 'selected' : '' }}>Above P50,000</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show/hide rejection reason field based on status selection
        const statusSelect = document.getElementById('verification_status');
        const rejectionField = document.getElementById('rejection_reason_field');
        
        if (statusSelect && rejectionField) {
            statusSelect.addEventListener('change', function() {
                if (this.value === 'rejected') {
                    rejectionField.style.display = 'block';
                    document.getElementById('rejection_reason').setAttribute('required', 'required');
                } else {
                    rejectionField.style.display = 'none';
                    document.getElementById('rejection_reason').removeAttribute('required');
                }
            });
        }
        
        // Initialize document image modals
        const documentModals = document.querySelectorAll('[id^="documentModal"]');
        documentModals.forEach(function(modal) {
            const modalObj = new bootstrap.Modal(modal);
            
            // Add click event to image previews to open modal
            const previewButtons = document.querySelectorAll(`.document-preview[data-target="${modal.id}"]`);
            previewButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    modalObj.show();
                });
            });
        });
    });
</script>
@endpush

<style>
/* Timeline Styles */
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline:before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 20px;
    width: 3px;
    background: #f1f1f1;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-icon {
    position: absolute;
    left: 10px;
    top: 0;
    width: 25px;
    height: 25px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
}

.timeline-content {
    margin-left: 50px;
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.timeline-header {
    margin-top: 0;
    font-weight: 600;
    font-size: 0.95rem;
}

.timeline-text {
    margin-bottom: 10px;
    font-size: 0.9rem;
}

.timeline-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* Document Preview Styles */
.document-image {
    border-radius: 5px;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    background-color: #f8f9fa;
    max-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.document-image img {
    max-height: 400px;
    object-fit: contain;
}

.document-preview {
    cursor: pointer;
    transition: transform 0.15s ease-in-out;
}

.document-preview:hover {
    transform: scale(1.03);
}
</style>
@endsection