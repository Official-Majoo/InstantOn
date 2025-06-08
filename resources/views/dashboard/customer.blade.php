@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-sm-flex align-items-center justify-content-between mb-2">
                    <h1 class="h3 mb-0">Welcome, {{ Auth::user()->name }}</h1>

                    @if ($status['is_verified'])
                        <span class="status-badge verified">
                            <i class="fas fa-check-circle me-1"></i> Verified Account
                        </span>
                    @elseif($status['is_rejected'])
                        <span class="status-badge rejected">
                            <i class="fas fa-times-circle me-1"></i> Registration Rejected
                        </span>
                    @elseif($status['is_pending'])
                        <span class="status-badge pending">
                            <i class="fas fa-clock me-1"></i> Verification Pending
                        </span>
                    @endif
                </div>
                <p class="text-muted">Manage your FNBB account and monitor your verification status.</p>
            </div>
        </div>

        @if ($status['is_rejected'])
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i> Registration Rejected</h4>
                <p>Unfortunately, your registration has been rejected for the following reason:</p>
                <p class="mb-0 font-weight-bold">{{ $status['rejection_reason'] }}</p>
                <hr>
                <p class="mb-0">For assistance, please contact our customer support at +267 370 6000 or visit your nearest
                    FNBB branch.</p>
            </div>
        @elseif(!$status['is_verified'] && !$status['is_rejected'])
            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading"><i class="fas fa-hourglass-half me-2"></i> Registration In Progress</h4>
                <p>Your registration is currently being processed. Please complete all required verification steps.</p>
                <hr>
                <p class="mb-0">Need help? Contact our customer support at +267 370 6000.</p>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h2 class="card-title h5">Registration Progress</h2>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="progress" style="height: 10px;">
                                @php
                                    $progress = 0;
                                    if ($customerProfile->verification_status === 'verified') {
                                        $progress += 25;
                                    }
                                    $documentCount = $documents->count();
                                    if ($documentCount > 0) {
                                        $progress += min(25, $documentCount * 8);
                                    }
                                    $hasVerifiedSession =
                                        $verificationSessions->where('status', 'approved')->count() > 0;
                                    if ($hasVerifiedSession) {
                                        $progress += 25;
                                    }
                                    if ($customerProfile->address) {
                                        $progress += 25;
                                    }
                                @endphp
                                <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%;"
                                    aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ $progress }}%</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <span>
                                        @if ($customerProfile->verification_status === 'verified')
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                        @else
                                            <i class="fas fa-circle text-muted me-2"></i>
                                        @endif
                                        Omang Verification
                                    </span>
                                    <a href="{{ route('verification.omang') }}" class="btn btn-sm btn-outline-primary">
                                        {{ $customerProfile->verification_status === 'verified' ? 'View' : 'Complete' }}
                                    </a>
                                </li>
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <span>
                                        @if ($documents->count() >= 3)
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                        @else
                                            <i class="fas fa-circle text-muted me-2"></i>
                                        @endif
                                        Document Upload
                                    </span>
                                    <a href="{{ route('verification.documents') }}" class="btn btn-sm btn-outline-primary">
                                        {{ $documents->count() >= 3 ? 'View' : 'Complete' }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <span>
                                        @if ($verificationSessions->where('status', 'approved')->count() > 0)
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                        @else
                                            <i class="fas fa-circle text-muted me-2"></i>
                                        @endif
                                        Facial Verification
                                    </span>
                                    <a href="{{ route('verification.facial') }}" class="btn btn-sm btn-outline-primary">
                                        {{ $verificationSessions->where('status', 'approved')->count() > 0 ? 'View' : 'Complete' }}
                                    </a>
                                </li>
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <span>
                                        @if ($customerProfile->address)
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                        @else
                                            <i class="fas fa-circle text-muted me-2"></i>
                                        @endif
                                        Additional Information
                                    </span>
                                    <a href="{{ route('verification.additional') }}"
                                        class="btn btn-sm btn-outline-primary">
                                        {{ $customerProfile->address ? 'View' : 'Complete' }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($status['is_verified'])
            <div class="row mb-4">
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="card dashboard-card shadow-sm">
                        <div class="card-body p-4">
                            <div class="dashboard-icon primary mx-auto">
                                <i class="fas fa-user"></i>
                            </div>
                            <h3 class="h5 text-center mb-3">Personal Profile</h3>
                            <p class="text-muted text-center small mb-4">View and manage your personal information and
                                contact details.</p>
                            <div class="d-grid">
                                <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-user-edit me-2"></i> Edit Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="card dashboard-card shadow-sm">
                        <div class="card-body p-4">
                            <div class="dashboard-icon tertiary mx-auto">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h3 class="h5 text-center mb-3">Documents</h3>
                            <p class="text-muted text-center small mb-4">Access and manage your verification documents and
                                uploads.</p>
                            <div class="d-grid">
                                <a href="{{ route('verification.documents') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-folder-open me-2"></i> View Documents
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card dashboard-card shadow-sm">
                        <div class="card-body p-4">
                            <div class="dashboard-icon success mx-auto">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h3 class="h5 text-center mb-3">Security</h3>
                            <p class="text-muted text-center small mb-4">Manage your account security and password settings.
                            </p>
                            <div class="d-grid">
                                <a href="{{ route('profile.edit') }}#update-password-form" class="btn btn-outline-primary">
                                    <i class="fas fa-lock me-2"></i> Update Password
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Customer Profile Information -->
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h2 class="card-title h5">Customer Information</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="h6 mb-3">Personal Details</h3>
                        <div class="customer-detail-row">
                            <div class="customer-detail-label">Name:</div>
                            <div>{{ $customerProfile->first_name }} {{ $customerProfile->middle_name }}
                                {{ $customerProfile->last_name }}</div>
                        </div>
                        <div class="customer-detail-row">
                            <div class="customer-detail-label">Omang Number:</div>
                            <div>{{ $customerProfile->omang_number }}</div>
                        </div>
                        <div class="customer-detail-row">
                            <div class="customer-detail-label">Date of Birth:</div>
                            <div>{{ $customerProfile->date_of_birth->format('d/m/Y') }}</div>
                        </div>
                        <div class="customer-detail-row">
                            <div class="customer-detail-label">Gender:</div>
                            <div>{{ ucfirst($customerProfile->gender) }}</div>
                        </div>
                        <div class="customer-detail-row">
                            <div class="customer-detail-label">Nationality:</div>
                            <div>{{ $customerProfile->nationality ?? 'Botswana' }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h3 class="h6 mb-3">Contact Information</h3>
                        <div class="customer-detail-row">
                            <div class="customer-detail-label">Email:</div>
                            <div>{{ Auth::user()->email }}</div>
                        </div>
                        <div class="customer-detail-row">
                            <div class="customer-detail-label">Phone:</div>
                            <div>{{ Auth::user()->phone_number }}</div>
                        </div>

                        @if ($customerProfile->address)
                            <div class="customer-detail-row">
                                <div class="customer-detail-label">Address:</div>
                                <div>{{ $customerProfile->address }}</div>
                            </div>
                            <div class="customer-detail-row">
                                <div class="customer-detail-label">City:</div>
                                <div>{{ $customerProfile->city }}</div>
                            </div>
                            <div class="customer-detail-row">
                                <div class="customer-detail-label">District:</div>
                                <div>{{ $customerProfile->district }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($customerProfile->address)
                    <hr class="my-4">

                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="h6 mb-3">Occupational Information</h3>
                            <div class="customer-detail-row">
                                <div class="customer-detail-label">Occupation:</div>
                                <div>{{ $customerProfile->occupation }}</div>
                            </div>
                            <div class="customer-detail-row">
                                <div class="customer-detail-label">Employer:</div>
                                <div>{{ $customerProfile->employer ?? 'Not specified' }}</div>
                            </div>
                            <div class="customer-detail-row">
                                <div class="customer-detail-label">Income Range:</div>
                                <div>
                                    @switch($customerProfile->income_range)
                                        @case('below_5000')
                                            Below 5,000 BWP
                                        @break

                                        @case('5000_to_10000')
                                            5,000 - 10,000 BWP
                                        @break

                                        @case('10001_to_25000')
                                            10,001 - 25,000 BWP
                                        @break

                                        @case('25001_to_50000')
                                            25,001 - 50,000 BWP
                                        @break

                                        @case('above_50000')
                                            Above 50,000 BWP
                                        @break

                                        @default
                                            Not specified
                                    @endswitch
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h3 class="h6 mb-3">Verification Status</h3>
                            <div class="customer-detail-row">
                                <div class="customer-detail-label">Status:</div>
                                <div>
                                    @if ($status['is_verified'])
                                        <span class="text-success"><i class="fas fa-check-circle me-1"></i>
                                            Verified</span>
                                    @elseif($status['is_rejected'])
                                        <span class="text-danger"><i class="fas fa-times-circle me-1"></i> Rejected</span>
                                    @else
                                        <span class="text-warning"><i class="fas fa-clock me-1"></i> Pending</span>
                                    @endif
                                </div>
                            </div>
                            @if ($status['is_verified'])
                                <div class="customer-detail-row">
                                    <div class="customer-detail-label">Verified On:</div>
                                    <div>{{ $customerProfile->updated_at->format('d/m/Y') }}</div>
                                </div>
                            @endif

                            <div class="customer-detail-row">
                                <div class="customer-detail-label">Account Created:</div>
                                <div>{{ $customerProfile->created_at->format('d/m/Y') }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="card-footer bg-white">
                @if ($status['is_verified'])
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-user-edit me-1"></i> Edit Profile
                    </a>
                @else
                    <div class="alert alert-info mb-0 py-2">
                        <small><i class="fas fa-info-circle me-1"></i> Complete all verification steps to fully activate
                            your account.</small>
                    </div>
                @endif
            </div>
        </div>

        <!-- Verification Sessions -->
        @if ($verificationSessions->count() > 0)
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h2 class="card-title h5">Verification History</h2>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Score</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($verificationSessions as $session)
                                    <tr>
                                        <td>{{ $session->created_at->format('d/m/Y H:i') }}</td>
                                        <td>Facial Verification</td>
                                        <td>{{ number_format($session->similarity_score, 1) }}%</td>
                                        <td>
                                            @if ($session->status === 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($session->status === 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $session->notes ?? 'No notes' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Documents -->
        @if ($documents->count() > 0)
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h2 class="card-title h5">Uploaded Documents</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($documents as $document)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    @if ($document->isImage())
                                        <a href="{{ route('document.view', $document->id) }}" target="_blank"
                                            class="document-link">
                                            <img src="{{ route('document.view', $document->id) }}"
                                                class="card-img-top document-image" alt="{{ $document->document_type }}">
                                        </a>
                                    @elseif($document->isPdf())
                                        <a href="{{ route('document.view', $document->id) }}" target="_blank"
                                            class="document-link">
                                            <div class="pdf-thumbnail">
                                                <i class="fas fa-file-pdf fa-4x text-danger"></i>
                                            </div>
                                        </a>
                                    @else
                                        <a href="{{ route('document.view', $document->id) }}" target="_blank"
                                            class="document-link">
                                            <div class="file-thumbnail">
                                                <i class="fas fa-file fa-4x text-secondary"></i>
                                            </div>
                                        </a>
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title h6">
                                            @switch($document->document_type)
                                                @case('omang_front')
                                                    Omang Front
                                                @break

                                                @case('omang_back')
                                                    Omang Back
                                                @break

                                                @case('proof_of_address')
                                                    Proof of Address
                                                @break

                                                @case('selfie')
                                                    Selfie Photo
                                                @break

                                                @default
                                                    {{ ucfirst(str_replace('_', ' ', $document->document_type)) }}
                                            @endswitch
                                        </h5>
                                        <p class="card-text small text-muted">
                                            Uploaded: {{ $document->uploaded_at->format('d/m/Y H:i') }}
                                        </p>
                                        <a href="{{ route('document.view', $document->id) }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        .document-image {
            height: 200px;
            object-fit: cover;
        }

        .pdf-thumbnail,
        .file-thumbnail {
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            flex-direction: column;
        }

        .document-link {
            display: block;
            text-decoration: none;
            color: inherit;
        }

        .document-link:hover {
            opacity: 0.9;
        }
    </style>
@endsection
