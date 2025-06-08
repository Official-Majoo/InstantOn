{{-- resources/views/dashboard/admin.blade.php --}}
@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<div class="dashboard-header mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold mb-1">Welcome back, {{ Auth::user()->name }}</h2>
            <p class="text-muted">Here's what's happening with your bank's registration system today.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.reports') }}" class="btn btn-primary">
                <i class="fas fa-file-alt me-2"></i> Generate Report
            </a>
            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#quickActionsModal">
                <i class="fas fa-bolt me-2"></i> Quick Actions
            </button>
        </div>
    </div>
</div>

<!-- Stats Overview Cards -->
<div class="row g-4 mb-4">
    <!-- Total Users Card -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon icon-primary">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Users</div>
                <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
                <div class="stat-change change-positive">
                    <i class="fas fa-arrow-up"></i> 3.48% <span class="text-muted">since last month</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Customers Card -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon icon-success">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Customers</div>
                <div class="stat-value">{{ number_format($stats['total_customers']) }}</div>
                <div class="stat-change change-positive">
                    <i class="fas fa-arrow-up"></i> 5.27% <span class="text-muted">since last month</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pending Approvals Card -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon icon-warning">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Pending Approvals</div>
                <div class="stat-value">{{ number_format($stats['pending_registrations']) }}</div>
                <div class="stat-change">
                    <a href="{{ route('admin.registrations', ['status' => 'pending']) }}" class="text-warning">
                        <i class="fas fa-exclamation-triangle"></i> Review now
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bank Officers Card -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon icon-info">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Bank Officers</div>
                <div class="stat-value">{{ number_format($stats['bank_officers']) }}</div>
                <div class="stat-change">
                    <a href="{{ route('admin.officers') }}" class="text-info">
                        <i class="fas fa-user-plus"></i> Manage officers
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Registration Trends Chart -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title">Registration Trends</h5>
                <div class="card-tools">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" id="viewLastQuarter">Last Quarter</a></li>
                            <li><a class="dropdown-item" href="#" id="viewLastYear">Last Year</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" id="downloadChart">Export Chart</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="registrationsChart"></canvas>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="fw-semibold text-success mb-1">{{ number_format($stats['verified_customers']) }}</div>
                        <div class="small text-muted">Verified</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-semibold text-warning mb-1">{{ number_format($stats['pending_registrations']) }}</div>
                        <div class="small text-muted">Pending</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-semibold text-danger mb-1">{{ number_format($stats['rejected_customers']) }}</div>
                        <div class="small text-muted">Rejected</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Verification Status Pie Chart -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title">Verification Status</h5>
                <div class="card-tools">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" id="togglePieLegend">Toggle Legend</a></li>
                            <li><a class="dropdown-item" href="#" id="downloadPieChart">Export Chart</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body d-flex align-items-center">
                <div class="chart-container">
                    <canvas id="statusPieChart"></canvas>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <div class="d-flex justify-content-around align-items-center">
                    <div class="text-center">
                        <span class="d-inline-block me-2" style="width: 12px; height: 12px; border-radius: 50%; background-color: #0CAA68;"></span>
                        <span class="small">Verified</span>
                    </div>
                    <div class="text-center">
                        <span class="d-inline-block me-2" style="width: 12px; height: 12px; border-radius: 50%; background-color: #FFC336;"></span>
                        <span class="small">Pending</span>
                    </div>
                    <div class="text-center">
                        <span class="d-inline-block me-2" style="width: 12px; height: 12px; border-radius: 50%; background-color: #E94E4D;"></span>
                        <span class="small">Rejected</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Recent Registrations -->
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title">Recent Registrations</h5>
                <div class="card-tools">
                    <a href="{{ route('admin.registrations') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="data-table w-100">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Omang</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRegistrations as $registration)
                            <tr>
                                <td>
                                    <div class="user-card">
                                        <div class="avatar">
                                            {{ substr($registration->first_name, 0, 1) }}{{ substr($registration->last_name, 0, 1) }}
                                        </div>
                                        <div class="user-info">
                                            <h6 class="user-name">{{ $registration->full_name }}</h6>
                                            <p class="user-email">{{ $registration->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                {{-- <td>{{ $registration->omang_ --}}
                                    <td>{{ $registration->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    @if($registration->verification_status === 'verified')
                                        <span class="status-indicator status-verified">
                                            <i class="fas fa-check-circle"></i> Verified
                                        </span>
                                    @elseif($registration->verification_status === 'rejected')
                                        <span class="status-indicator status-rejected">
                                            <i class="fas fa-times-circle"></i> Rejected
                                        </span>
                                    @else
                                        <span class="status-indicator status-pending">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.customer.view', $registration->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#customerModal{{ $registration->id }}" title="Quick View">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No recent registrations found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-transparent text-center">
                <a href="{{ route('admin.registrations') }}" class="text-primary fw-medium">
                    View All Registrations <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title">Recent Activity</h5>
                <div class="card-tools">
                    <a href="{{ route('admin.activity') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="timeline">
                    @forelse($recentActivities ?? [] as $activity)
                    <div class="timeline-item">
                        <div class="timeline-icon {{ $activity->log_name === 'customer_review_submitted' ? 'success' : ($activity->log_name === 'facial_verification_attempt' ? 'info' : 'primary') }}"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-medium">{{ $activity->causer->name ?? 'System' }}</span>
                                <span class="timeline-time">{{ $activity->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="mb-1">{{ $activity->description }}</p>
                            @if($activity->log_name === 'customer_review_submitted')
                                <div class="small text-muted">
                                    Status: <span class="fw-medium">{{ ucfirst($activity->properties['status'] ?? 'reviewed') }}</span>
                                    @if(isset($activity->properties['notes']))
                                        <br>Note: "{{ Str::limit($activity->properties['notes'], 60) }}"
                                    @endif
                                </div>
                            @elseif($activity->log_name === 'facial_verification_attempt')
                                <div class="small text-muted">
                                    Similarity score: <span class="fw-medium">{{ $activity->properties['similarity_score'] ?? '0' }}%</span>
                                    <span class="ms-2 {{ $activity->properties['passed'] ? 'text-success' : 'text-danger' }}">
                                        ({{ $activity->properties['passed'] ? 'Passed' : 'Failed' }})
                                    </span>
                                </div>
                            @endif
                            @if($activity->subject)
                                <div class="mt-2">
                                    <a href="{{ route('admin.customer.view', $activity->subject_id) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <div class="mb-3">
                            <i class="fas fa-history text-muted fa-2x"></i>
                        </div>
                        <p class="text-muted">No recent activities found</p>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="card-footer bg-transparent text-center">
                <a href="{{ route('admin.activity') }}" class="text-primary fw-medium">
                    View All Activity <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- User Management Card -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">User Management</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.users') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle icon-primary me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">View All Users</h6>
                                <small class="text-muted">Manage all system users</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary rounded-pill me-2">{{ number_format($stats['total_users']) }}</span>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.users.create') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle icon-success me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Add New User</h6>
                                <small class="text-muted">Create user account</small>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </a>
                    
                    <a href="{{ route('admin.officers') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle icon-info me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Manage Bank Officers</h6>
                                <small class="text-muted">View and manage officer accounts</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-info rounded-pill me-2">{{ number_format($stats['bank_officers']) }}</span>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.roles') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle icon-warning me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Role Management</h6>
                                <small class="text-muted">Configure user roles and permissions</small>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- System Configuration -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">System Configuration</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.settings') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle icon-primary me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">General Settings</h6>
                                <small class="text-muted">Configure system preferences</small>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </a>
                    
                    <a href="{{ route('admin.security') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle icon-danger me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Security Settings</h6>
                                <small class="text-muted">Configure security parameters</small>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </a>
                    
                    <a href="{{ route('admin.api') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle icon-info me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">API Configuration</h6>
                                <small class="text-muted">Configure external integrations</small>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </a>
                    
                    <a href="{{ route('admin.logs') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle icon-secondary me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                <i class="fas fa-history"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">System Logs</h6>
                                <small class="text-muted">View system activity logs</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            @if($newLogsCount ?? 0 > 0)
                                <span class="badge bg-danger rounded-pill me-2">{{ $newLogsCount }}</span>
                            @endif
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customer Quick View Modals -->
@foreach($recentRegistrations as $registration)
<div class="modal fade" id="customerModal{{ $registration->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer Quick View</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="avatar me-3" style="width: 64px; height: 64px; font-size: 1.5rem;">
                        {{ substr($registration->first_name, 0, 1) }}{{ substr($registration->last_name, 0, 1) }}
                    </div>
                    <div>
                        <h4 class="mb-0">{{ $registration->full_name }}</h4>
                        <div class="text-muted">{{ $registration->user->email }}</div>
                        @if($registration->verification_status === 'verified')
                            <span class="status-indicator status-verified mt-2">
                                <i class="fas fa-check-circle"></i> Verified
                            </span>
                        @elseif($registration->verification_status === 'rejected')
                            <span class="status-indicator status-rejected mt-2">
                                <i class="fas fa-times-circle"></i> Rejected
                            </span>
                        @else
                            <span class="status-indicator status-pending mt-2">
                                <i class="fas fa-clock"></i> Pending
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header py-2">
                                <h6 class="card-title mb-0">Personal Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Full Name</label>
                                    <div class="fw-medium">{{ $registration->full_name }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Omang Number</label>
                                    <div class="fw-medium">{{ $registration->omang_number }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Date of Birth</label>
                                    <div class="fw-medium">{{ $registration->date_of_birth->format('d/m/Y') }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Gender</label>
                                    <div class="fw-medium">{{ ucfirst($registration->gender) }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Email</label>
                                    <div class="fw-medium">{{ $registration->user->email }}</div>
                                </div>
                                <div>
                                    <label class="form-label text-muted small mb-1">Phone</label>
                                    <div class="fw-medium">{{ $registration->user->phone_number }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header py-2">
                                <h6 class="card-title mb-0">Verification Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Registration Date</label>
                                    <div class="fw-medium">{{ $registration->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Documents</label>
                                    <div class="fw-medium">{{ $registration->documents->count() }} document(s) uploaded</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Facial Verification</label>
                                    @php
                                        $verificationSession = $registration->verificationSessions->first();
                                    @endphp
                                    <div class="fw-medium">
                                        @if($verificationSession)
                                            {{ number_format($verificationSession->similarity_score, 1) }}% similarity
                                            <div class="progress mt-1" style="height: 6px;">
                                                <div class="progress-bar bg-{{ $verificationSession->similarity_score >= 80 ? 'success' : ($verificationSession->similarity_score >= 60 ? 'warning' : 'danger') }}" 
                                                    role="progressbar" 
                                                    style="width: {{ $verificationSession->similarity_score }}%;" 
                                                    aria-valuenow="{{ $verificationSession->similarity_score }}" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100"></div>
                                            </div>
                                        @else
                                            <span class="text-muted">Not completed</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Status</label>
                                    <div class="fw-medium">
                                        @if($registration->verification_status === 'verified')
                                            <span class="text-success">Verified</span>
                                        @elseif($registration->verification_status === 'rejected')
                                            <span class="text-danger">Rejected</span>
                                        @else
                                            <span class="text-warning">Pending</span>
                                        @endif
                                    </div>
                                </div>
                                @if($registration->verification_status === 'rejected')
                                <div>
                                    <label class="form-label text-muted small mb-1">Rejection Reason</label>
                                    <div class="fw-medium text-danger">{{ $registration->rejection_reason }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('admin.customer.view', $registration->id) }}" class="btn btn-primary">View Full Details</a>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Quick Actions Modal -->
<div class="modal fade" id="quickActionsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="list-group">
                    <a href="{{ route('admin.registrations', ['status' => 'pending']) }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <div class="icon-circle icon-warning me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Review Pending Registrations</h6>
                            <small class="text-muted">Process customer verification requests</small>
                        </div>
                    </a>
                    <a href="{{ route('admin.users.create') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <div class="icon-circle icon-success me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Add New User</h6>
                            <small class="text-muted">Create new system user</small>
                        </div>
                    </a>
                    <a href="{{ route('admin.reports') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <div class="icon-circle icon-primary me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Generate Report</h6>
                            <small class="text-muted">Create system activity report</small>
                        </div>
                    </a>
                    <a href="{{ route('admin.security') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <div class="icon-circle icon-danger me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Security Settings</h6>
                            <small class="text-muted">Configure security parameters</small>
                        </div>
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Registration Chart
    const monthlyData = @json($monthlyTrends ?? []);
    const labels = Object.keys(monthlyData).map(month => {
        const [year, monthNum] = month.split('-');
        return new Date(year, monthNum - 1).toLocaleString('default', { month: 'short' }) + ' ' + year;
    });
    const data = Object.values(monthlyData);

    const ctx = document.getElementById('registrationsChart').getContext('2d');
    const registrationsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Registrations',
                data: data,
                backgroundColor: 'rgba(2, 92, 122, 0.1)',
                borderColor: '#025C7A',
                borderWidth: 2,
                pointBackgroundColor: '#025C7A',
                pointBorderColor: '#fff',
                pointRadius: 4,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: "rgba(0, 0, 0, 0.05)"
                    },
                    ticks: {
                        precision: 0
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    }
                }
            }
        }
    });

    // Status Pie Chart
    const pieCtx = document.getElementById('statusPieChart').getContext('2d');
    const statusPieChart = new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: ['Verified', 'Pending', 'Rejected'],
            datasets: [{
                data: [
                    {{ $stats['verified_customers'] ?? 0 }},
                    {{ $stats['pending_registrations'] ?? 0 }},
                    {{ $stats['rejected_customers'] ?? 0 }}
                ],
                backgroundColor: [
                    '#0CAA68', // success
                    '#FFC336', // warning
                    '#E94E4D'  // danger
                ],
                borderColor: [
                    '#0CAA68',
                    '#FFC336',
                    '#E94E4D'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    }
                }
            }
        }
    });

    // Event handlers for chart controls
    document.getElementById('viewLastQuarter')?.addEventListener('click', function(e) {
        e.preventDefault();
        if (labels.length > 3) {
            registrationsChart.data.labels = labels.slice(-3);
            registrationsChart.data.datasets[0].data = data.slice(-3);
            registrationsChart.update();
        }
    });
    
    document.getElementById('viewLastYear')?.addEventListener('click', function(e) {
        e.preventDefault();
        registrationsChart.data.labels = labels;
        registrationsChart.data.datasets[0].data = data;
        registrationsChart.update();
    });
    
    document.getElementById('togglePieLegend')?.addEventListener('click', function(e) {
        e.preventDefault();
        statusPieChart.options.plugins.legend.display = !statusPieChart.options.plugins.legend.display;
        statusPieChart.update();
    });
    
    document.getElementById('downloadChart')?.addEventListener('click', function(e) {
        e.preventDefault();
        downloadChartAsImage('registrationsChart', 'fnbb-registrations-chart.png');
    });
    
    document.getElementById('downloadPieChart')?.addEventListener('click', function(e) {
        e.preventDefault();
        downloadChartAsImage('statusPieChart', 'fnbb-status-pie-chart.png');
    });
    
    // Helper function to download chart as image
    function downloadChartAsImage(chartId, filename) {
        const canvas = document.getElementById(chartId);
        const image = canvas.toDataURL('image/png');
        const link = document.createElement('a');
        link.download = filename;
        link.href = image;
        link.click();
    }
});
</script>
@endpush
@endsection{{-- resources/views/dashboard/admin.blade.php --}}
@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<div class="dashboard-header mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold mb-1">Welcome back, {{ Auth::user()->name }}</h2>
            <p class="text-muted">Here's what's happening with your bank's registration system today.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.reports') }}" class="btn btn-primary">
                <i class="fas fa-file-alt me-2"></i> Generate Report
            </a>
            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#quickActionsModal">
                <i class="fas fa-bolt me-2"></i> Quick Actions
            </button>
        </div>
    </div>
</div>

<!-- Stats Overview Cards -->
<div class="row g-4 mb-4">
    <!-- Total Users Card -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon icon-primary">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Users</div>
                <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
                <div class="stat-change change-positive">
                    <i class="fas fa-arrow-up"></i> 3.48% <span class="text-muted">since last month</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Customers Card -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon icon-success">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Customers</div>
                <div class="stat-value">{{ number_format($stats['total_customers']) }}</div>
                <div class="stat-change change-positive">
                    <i class="fas fa-arrow-up"></i> 5.27% <span class="text-muted">since last month</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pending Approvals Card -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon icon-warning">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Pending Approvals</div>
                <div class="stat-value">{{ number_format($stats['pending_registrations']) }}</div>
                <div class="stat-change">
                    <a href="{{ route('admin.registrations', ['status' => 'pending']) }}" class="text-warning">
                        <i class="fas fa-exclamation-triangle"></i> Review now
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bank Officers Card -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon icon-info">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Bank Officers</div>
                <div class="stat-value">{{ number_format($stats['bank_officers']) }}</div>
                <div class="stat-change">
                    <a href="{{ route('admin.officers') }}" class="text-info">
                        <i class="fas fa-user-plus"></i> Manage officers
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Registration Trends Chart -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title">Registration Trends</h5>
                <div class="card-tools">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" id="viewLastQuarter">Last Quarter</a></li>
                            <li><a class="dropdown-item" href="#" id="viewLastYear">Last Year</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" id="downloadChart">Export Chart</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="registrationsChart"></canvas>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="fw-semibold text-success mb-1">{{ number_format($stats['verified_customers']) }}</div>
                        <div class="small text-muted">Verified</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-semibold text-warning mb-1">{{ number_format($stats['pending_registrations']) }}</div>
                        <div class="small text-muted">Pending</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-semibold text-danger mb-1">{{ number_format($stats['rejected_customers']) }}</div>
                        <div class="small text-muted">Rejected</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Verification Status Pie Chart -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title">Verification Status</h5>
                <div class="card-tools">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" id="togglePieLegend">Toggle Legend</a></li>
                            <li><a class="dropdown-item" href="#" id="downloadPieChart">Export Chart</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body d-flex align-items-center">
                <div class="chart-container">
                    <canvas id="statusPieChart"></canvas>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <div class="d-flex justify-content-around align-items-center">
                    <div class="text-center">
                        <span class="d-inline-block me-2" style="width: 12px; height: 12px; border-radius: 50%; background-color: #0CAA68;"></span>
                        <span class="small">Verified</span>
                    </div>
                    <div class="text-center">
                        <span class="d-inline-block me-2" style="width: 12px; height: 12px; border-radius: 50%; background-color: #FFC336;"></span>
                        <span class="small">Pending</span>
                    </div>
                    <div class="text-center">
                        <span class="d-inline-block me-2" style="width: 12px; height: 12px; border-radius: 50%; background-color: #E94E4D;"></span>
                        <span class="small">Rejected</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Recent Registrations -->
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title">Recent Registrations</h5>
                <div class="card-tools">
                    <a href="{{ route('admin.registrations') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="data-table w-100">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Omang</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRegistrations as $registration)
                            <tr>
                                <td>
                                    <div class="user-card">
                                        <div class="avatar">
                                            {{ substr($registration->first_name, 0, 1) }}{{ substr($registration->last_name, 0, 1) }}
                                        </div>
                                        <div class="user-info">
                                            <h6 class="user-name">{{ $registration->full_name }}</h6>
                                            <p class="user-email">{{ $registration->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $registration->omang_number }}
                                    </td>
                                <td>{{ $registration->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    @if($registration->verification_status === 'verified')
                                        <span class="status-indicator status-verified">
                                            <i class="fas fa-check-circle"></i> Verified
                                        </span>
                                    @elseif($registration->verification_status === 'rejected')
                                        <span class="status-indicator status-rejected">
                                            <i class="fas fa-times-circle"></i> Rejected
                                        </span>
                                    @else
                                        <span class="status-indicator status-pending">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.customer.view', $registration->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#customerModal{{ $registration->id }}" title="Quick View">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No recent registrations found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-transparent text-center">
                <a href="{{ route('admin.registrations') }}" class="text-primary fw-medium">
                    View All Registrations <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title">Recent Activity</h5>
                <div class="card-tools">
                    <a href="{{ route('admin.activity') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="timeline">
                    @forelse($recentActivities ?? [] as $activity)
                    <div class="timeline-item">
                        <div class="timeline-icon {{ $activity->log_name === 'customer_review_submitted' ? 'success' : ($activity->log_name === 'facial_verification_attempt' ? 'info' : 'primary') }}"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-medium">{{ $activity->causer->name ?? 'System' }}</span>
                                <span class="timeline-time">{{ $activity->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="mb-1">{{ $activity->description }}</p>
                            @if($activity->log_name === 'customer_review_submitted')
                                <div class="small text-muted">
                                    Status: <span class="fw-medium">{{ ucfirst($activity->properties['status'] ?? 'reviewed') }}</span>
                                    @if(isset($activity->properties['notes']))
                                        <br>Note: "{{ Str::limit($activity->properties['notes'], 60) }}"
                                    @endif
                                </div>
                            @elseif($activity->log_name === 'facial_verification_attempt')
                                <div class="small text-muted">
                                    Similarity score: <span class="fw-medium">{{ $activity->properties['similarity_score'] ?? '0' }}%</span>
                                    <span class="ms-2 {{ $activity->properties['passed'] ? 'text-success' : 'text-danger' }}">
                                        ({{ $activity->properties['passed'] ? 'Passed' : 'Failed' }})
                                    </span>
                                </div>
                            @endif
                            @if($activity->subject)
                                <div class="mt-2">
                                    <a href="{{ route('admin.customer.view', $activity->subject_id) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <div class="mb-3">
                            <i class="fas fa-history text-muted fa-2x"></i>
                        </div>
                        <p class="text-muted">No recent activities found</p>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="card-footer bg-transparent text-center">
                <a href="{{ route('admin.activity') }}" class="text-primary fw-medium">
                    View All Activity <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- User Management Card -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">User Management</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.users') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle icon-primary me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">View All Users</h6>
                                <small class="text-muted">Manage all system users</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary rounded-pill me-2">{{ number_format($stats['total_users']) }}</span>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.users.create') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle icon-success me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Add New User</h6>
                                <small class="text-muted">Create user account</small>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </a>
                    
                    <a href="{{ route('admin.officers') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle icon-info me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Manage Bank Officers</h6>
                                <small class="text-muted">View and manage officer accounts</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-info rounded-pill me-2">{{ number_format($stats['bank_officers']) }}</span>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.roles') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle icon-warning me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Role Management</h6>
                                <small class="text-muted">Configure user roles and permissions</small>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- System Configuration -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">System Configuration</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.settings') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle icon-primary me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">General Settings</h6>
                                <small class="text-muted">Configure system preferences</small>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </a>
                    
                    <a href="{{ route('admin.security') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle icon-danger me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Security Settings</h6>
                                <small class="text-muted">Configure security parameters</small>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </a>
                    
                    <a href="{{ route('admin.api') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle icon-info me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">API Configuration</h6>
                                <small class="text-muted">Configure external integrations</small>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </a>
                    
                    <a href="{{ route('admin.logs') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle icon-secondary me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                <i class="fas fa-history"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">System Logs</h6>
                                <small class="text-muted">View system activity logs</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            @if($newLogsCount ?? 0 > 0)
                                <span class="badge bg-danger rounded-pill me-2">{{ $newLogsCount }}</span>
                            @endif
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customer Quick View Modals -->
@foreach($recentRegistrations as $registration)
<div class="modal fade" id="customerModal{{ $registration->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer Quick View</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="avatar me-3" style="width: 64px; height: 64px; font-size: 1.5rem;">
                        {{ substr($registration->first_name, 0, 1) }}{{ substr($registration->last_name, 0, 1) }}
                    </div>
                    <div>
                        <h4 class="mb-0">{{ $registration->full_name }}</h4>
                        <div class="text-muted">{{ $registration->user->email }}</div>
                        @if($registration->verification_status === 'verified')
                            <span class="status-indicator status-verified mt-2">
                                <i class="fas fa-check-circle"></i> Verified
                            </span>
                        @elseif($registration->verification_status === 'rejected')
                            <span class="status-indicator status-rejected mt-2">
                                <i class="fas fa-times-circle"></i> Rejected
                            </span>
                        @else
                            <span class="status-indicator status-pending mt-2">
                                <i class="fas fa-clock"></i> Pending
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header py-2">
                                <h6 class="card-title mb-0">Personal Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Full Name</label>
                                    <div class="fw-medium">{{ $registration->full_name }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Omang Number</label>
                                    <div class="fw-medium">{{ $registration->omang_number }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Date of Birth</label>
                                    <div class="fw-medium">{{ $registration->date_of_birth->format('d/m/Y') }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Gender</label>
                                    <div class="fw-medium">{{ ucfirst($registration->gender) }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Email</label>
                                    <div class="fw-medium">{{ $registration->user->email }}</div>
                                </div>
                                <div>
                                    <label class="form-label text-muted small mb-1">Phone</label>
                                    <div class="fw-medium">{{ $registration->user->phone_number }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header py-2">
                                <h6 class="card-title mb-0">Verification Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Registration Date</label>
                                    <div class="fw-medium">{{ $registration->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Documents</label>
                                    <div class="fw-medium">{{ $registration->documents->count() }} document(s) uploaded</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Facial Verification</label>
                                    @php
                                        $verificationSession = $registration->verificationSessions->first();
                                    @endphp
                                    <div class="fw-medium">
                                        @if($verificationSession)
                                            {{ number_format($verificationSession->similarity_score, 1) }}% similarity
                                            <div class="progress mt-1" style="height: 6px;">
                                                <div class="progress-bar bg-{{ $verificationSession->similarity_score >= 80 ? 'success' : ($verificationSession->similarity_score >= 60 ? 'warning' : 'danger') }}" 
                                                    role="progressbar" 
                                                    style="width: {{ $verificationSession->similarity_score }}%;" 
                                                    aria-valuenow="{{ $verificationSession->similarity_score }}" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100"></div>
                                            </div>
                                        @else
                                            <span class="text-muted">Not completed</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Status</label>
                                    <div class="fw-medium">
                                        @if($registration->verification_status === 'verified')
                                            <span class="text-success">Verified</span>
                                        @elseif($registration->verification_status === 'rejected')
                                            <span class="text-danger">Rejected</span>
                                        @else
                                            <span class="text-warning">Pending</span>
                                        @endif
                                    </div>
                                </div>
                                @if($registration->verification_status === 'rejected')
                                <div>
                                    <label class="form-label text-muted small mb-1">Rejection Reason</label>
                                    <div class="fw-medium text-danger">{{ $registration->rejection_reason }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('admin.customer.view', $registration->id) }}" class="btn btn-primary">View Full Details</a>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Quick Actions Modal -->
<div class="modal fade" id="quickActionsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="list-group">
                    <a href="{{ route('admin.registrations', ['status' => 'pending']) }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <div class="icon-circle icon-warning me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Review Pending Registrations</h6>
                            <small class="text-muted">Process customer verification requests</small>
                        </div>
                    </a>
                    <a href="{{ route('admin.users.create') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <div class="icon-circle icon-success me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Add New User</h6>
                            <small class="text-muted">Create new system user</small>
                        </div>
                    </a>
                    <a href="{{ route('admin.reports') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <div class="icon-circle icon-primary me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Generate Report</h6>
                            <small class="text-muted">Create system activity report</small>
                        </div>
                    </a>
                    <a href="{{ route('admin.security') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <div class="icon-circle icon-danger me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Security Settings</h6>
                            <small class="text-muted">Configure security parameters</small>
                        </div>
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Registration Chart
    const monthlyData = @json($monthlyTrends ?? []);
    const labels = Object.keys(monthlyData).map(month => {
        const [year, monthNum] = month.split('-');
        return new Date(year, monthNum - 1).toLocaleString('default', { month: 'short' }) + ' ' + year;
    });
    const data = Object.values(monthlyData);

    const ctx = document.getElementById('registrationsChart').getContext('2d');
    const registrationsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Registrations',
                data: data,
                backgroundColor: 'rgba(2, 92, 122, 0.1)',
                borderColor: '#025C7A',
                borderWidth: 2,
                pointBackgroundColor: '#025C7A',
                pointBorderColor: '#fff',
                pointRadius: 4,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: "rgba(0, 0, 0, 0.05)"
                    },
                    ticks: {
                        precision: 0
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    }
                }
            }
        }
    });

    // Status Pie Chart
    const pieCtx = document.getElementById('statusPieChart').getContext('2d');
    const statusPieChart = new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: ['Verified', 'Pending', 'Rejected'],
            datasets: [{
                data: [
                    {{ $stats['verified_customers'] ?? 0 }},
                    {{ $stats['pending_registrations'] ?? 0 }},
                    {{ $stats['rejected_customers'] ?? 0 }}
                ],
                backgroundColor: [
                    '#0CAA68', // success
                    '#FFC336', // warning
                    '#E94E4D'  // danger
                ],
                borderColor: [
                    '#0CAA68',
                    '#FFC336',
                    '#E94E4D'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    }
                }
            }
        }
    });

    // Event handlers for chart controls
    document.getElementById('viewLastQuarter')?.addEventListener('click', function(e) {
        e.preventDefault();
        if (labels.length > 3) {
            registrationsChart.data.labels = labels.slice(-3);
            registrationsChart.data.datasets[0].data = data.slice(-3);
            registrationsChart.update();
        }
    });
    
    document.getElementById('viewLastYear')?.addEventListener('click', function(e) {
        e.preventDefault();
        registrationsChart.data.labels = labels;
        registrationsChart.data.datasets[0].data = data;
        registrationsChart.update();
    });
    
    document.getElementById('togglePieLegend')?.addEventListener('click', function(e) {
        e.preventDefault();
        statusPieChart.options.plugins.legend.display = !statusPieChart.options.plugins.legend.display;
        statusPieChart.update();
    });
    
    document.getElementById('downloadChart')?.addEventListener('click', function(e) {
        e.preventDefault();
        downloadChartAsImage('registrationsChart', 'fnbb-registrations-chart.png');
    });
    
    document.getElementById('downloadPieChart')?.addEventListener('click', function(e) {
        e.preventDefault();
        downloadChartAsImage('statusPieChart', 'fnbb-status-pie-chart.png');
    });
    
    // Helper function to download chart as image
    function downloadChartAsImage(chartId, filename) {
        const canvas = document.getElementById(chartId);
        const image = canvas.toDataURL('image/png');
        const link = document.createElement('a');
        link.download = filename;
        link.href = image;
        link.click();
    }
});
</script>
@endpush
@endsection