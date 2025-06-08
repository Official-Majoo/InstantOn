@extends('layouts.admin')

@section('title', 'Customer Registrations')
@section('page_title', 'Customer Registrations')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active" aria-current="page">Registrations</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Customer Registrations</h2>
        <p class="text-muted">Manage and monitor all customer registration applications.</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#filtersModal">
            <i class="fas fa-filter me-2"></i> Advanced Filters
        </button>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-plus me-2"></i> Actions
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#" id="exportRegistrations">
                    <i class="fas fa-file-export me-2"></i> Export List
                </a></li>
                <li><a class="dropdown-item" href="{{ route('admin.reports') }}">
                    <i class="fas fa-chart-bar me-2"></i> Generate Report
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">
                    <i class="fas fa-envelope me-2"></i> Email All Pending
                </a></li>
            </ul>
        </div>
    </div>
</div>

<!-- Status Quick Filters -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('admin.registrations') }}" class="text-decoration-none">
            <div class="stat-card">
                <div class="stat-icon icon-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">All Registrations</div>
                    <div class="stat-value">{{ number_format($registrations->total()) }}</div>
                    <div class="stat-change">
                        <span class="text-muted">Total registrations</span>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('admin.registrations', ['status' => 'pending']) }}" class="text-decoration-none">
            <div class="stat-card {{ request('status') === 'pending' ? 'border border-2 border-warning' : '' }}">
                <div class="stat-icon icon-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Pending</div>
                    <div class="stat-value" id="pendingCount">
                        {{ number_format($pendingCount ?? $registrations->where('verification_status', 'pending')->count()) }}
                    </div>
                    <div class="stat-change">
                        <span class="text-warning">Require review</span>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('admin.registrations', ['status' => 'verified']) }}" class="text-decoration-none">
            <div class="stat-card {{ request('status') === 'verified' ? 'border border-2 border-success' : '' }}">
                <div class="stat-icon icon-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Verified</div>
                    <div class="stat-value" id="verifiedCount">
                        {{ number_format($verifiedCount ?? $registrations->where('verification_status', 'verified')->count()) }}
                    </div>
                    <div class="stat-change">
                        <span class="text-success">Approved customers</span>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('admin.registrations', ['status' => 'rejected']) }}" class="text-decoration-none">
            <div class="stat-card {{ request('status') === 'rejected' ? 'border border-2 border-danger' : '' }}">
                <div class="stat-icon icon-danger">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Rejected</div>
                    <div class="stat-value" id="rejectedCount">
                        {{ number_format($rejectedCount ?? $registrations->where('verification_status', 'rejected')->count()) }}
                    </div>
                    <div class="stat-change">
                        <span class="text-danger">Declined applications</span>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Search Bar -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.registrations') }}" method="GET" class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="search" name="search" placeholder="Search by name, Omang, email..." value="{{ request('search') }}">
                    @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    @if(request('direction'))
                    <input type="hidden" name="direction" value="{{ request('direction') }}">
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <select class="form-select" id="sort" name="sort" onchange="this.form.submit()">
                    <option value="created_at" {{ request('sort') === 'created_at' || !request('sort') ? 'selected' : '' }}>Sort by Date</option>
                    <option value="first_name" {{ request('sort') === 'first_name' ? 'selected' : '' }}>Sort by Name</option>
                    <option value="omang_number" {{ request('sort') === 'omang_number' ? 'selected' : '' }}>Sort by Omang</option>
                    <option value="verification_status" {{ request('sort') === 'verification_status' ? 'selected' : '' }}>Sort by Status</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" id="direction" name="direction" onchange="this.form.submit()">
                    <option value="desc" {{ request('direction') === 'desc' || !request('direction') ? 'selected' : '' }}>Descending</option>
                    <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>Ascending</option>
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Registrations Table -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            @if(request('status'))
                {{ ucfirst(request('status')) }} Registrations
            @else
                All Registrations
            @endif
        </h5>
        <div class="dropdown">
            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-ellipsis-v"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#" id="refreshTable">
                    <i class="fas fa-sync-alt me-2"></i> Refresh
                </a></li>
                <li><a class="dropdown-item" href="#" id="exportTableCSV">
                    <i class="fas fa-file-csv me-2"></i> Export as CSV
                </a></li>
                <li><a class="dropdown-item" href="#" id="exportTableExcel">
                    <i class="fas fa-file-excel me-2"></i> Export as Excel
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#" id="printTable">
                    <i class="fas fa-print me-2"></i> Print
                </a></li>
            </ul>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="data-table w-100">
                <thead>
                    <tr>
                        <th style="width: 35%">Customer</th>
                        <th style="width: 15%">Omang</th>
                        <th style="width: 15%">Date</th>
                        <th style="width: 15%">Status</th>
                        <th style="width: 20%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $registration)
                    <tr>
                        <td>
                            <div class="user-card">
                                <div class="avatar">
                                    {{ substr($registration->first_name, 0, 1) }}{{ substr($registration->last_name, 0, 1) }}
                                </div>
                                <div class="user-info">
                                    <h6 class="user-name">{{ $registration->full_name }}</h6>
                                    <p class="user-email">
                                        {{ $registration->user->email }}
                                        @if($registration->user->phone_number)
                                        <span class="d-block">{{ $registration->user->phone_number }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td>{{ $registration->omang_number }}</td>
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
                                
                                @if($registration->verification_status === 'pending')
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Update Status
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <button type="button" class="dropdown-item text-success" data-bs-toggle="modal" data-bs-target="#verifyModal{{ $registration->id }}">
                                                <i class="fas fa-check-circle me-2"></i> Verify
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $registration->id }}">
                                                <i class="fas fa-times-circle me-2"></i> Reject
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                @else
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('admin.customer.view', $registration->id) }}">
                                            <i class="fas fa-eye me-2 text-primary"></i> View Details
                                        </a></li>
                                        <li><a class="dropdown-item" href="#">
                                            <i class="fas fa-envelope me-2 text-info"></i> Send Email
                                        </a></li>
                                        <li><a class="dropdown-item" href="#">
                                            <i class="fas fa-comment-alt me-2 text-warning"></i> Send SMS
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <button type="button" class="dropdown-item text-secondary" data-bs-toggle="modal" data-bs-target="#resetStatusModal{{ $registration->id }}">
                                                <i class="fas fa-redo me-2"></i> Reset Status
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <div class="py-5">
                                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                <h5>No registrations found</h5>
                                <p class="text-muted">Try adjusting your search or filter criteria</p>
                                <a href="{{ route('admin.registrations') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-redo me-2"></i> Reset Filters
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-transparent">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Showing {{ $registrations->firstItem() ?? 0 }} to {{ $registrations->lastItem() ?? 0 }} of {{ $registrations->total() }} registrations
            </div>
            <div>
                {{ $registrations->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Verify Modals -->
@foreach($registrations as $registration)
@if($registration->verification_status === 'pending')
<div class="modal fade" id="verifyModal{{ $registration->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verify Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.customer.status', $registration->id) }}" method="POST">
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
                    
                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar me-3">
                                {{ substr($registration->first_name, 0, 1) }}{{ substr($registration->last_name, 0, 1) }}
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $registration->full_name }}</h6>
                                <p class="text-muted mb-0">{{ $registration->omang_number }}</p>
                            </div>
                        </div>
                        
                        <div class="small">
                            <div class="row mb-2">
                                <div class="col-4 text-muted">Email:</div>
                                <div class="col-8">{{ $registration->user->email }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 text-muted">Phone:</div>
                                <div class="col-8">{{ $registration->user->phone_number }}</div>
                            </div>
                            <div class="row">
                                <div class="col-4 text-muted">Registered:</div>
                                <div class="col-8">{{ $registration->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notesVerify{{ $registration->id }}" class="form-label">Verification Notes (Optional)</label>
                        <textarea class="form-control" id="notesVerify{{ $registration->id }}" name="notes" rows="3" placeholder="Add any notes about this verification..."></textarea>
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

<div class="modal fade" id="rejectModal{{ $registration->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.customer.status', $registration->id) }}" method="POST">
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
                    
                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar me-3">
                                {{ substr($registration->first_name, 0, 1) }}{{ substr($registration->last_name, 0, 1) }}
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $registration->full_name }}</h6>
                                <p class="text-muted mb-0">{{ $registration->omang_number }}</p>
                            </div>
                        </div>
                        
                        <div class="small">
                            <div class="row mb-2">
                                <div class="col-4 text-muted">Email:</div>
                                <div class="col-8">{{ $registration->user->email }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4 text-muted">Phone:</div>
                                <div class="col-8">{{ $registration->user->phone_number }}</div>
                            </div>
                            <div class="row">
                                <div class="col-4 text-muted">Registered:</div>
                                <div class="col-8">{{ $registration->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="rejectionReason{{ $registration->id }}" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejectionReason{{ $registration->id }}" name="rejection_reason" rows="3" placeholder="Please provide the reason for rejection..." required></textarea>
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
@else
<div class="modal fade" id="resetStatusModal{{ $registration->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reset Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.customer.status', $registration->id) }}" method="POST">
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
                    
                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar me-3">
                                {{ substr($registration->first_name, 0, 1) }}{{ substr($registration->last_name, 0, 1) }}
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $registration->full_name }}</h6>
                                <p class="text-muted mb-0">{{ $registration->omang_number }}</p>
                            </div>
                        </div>
                        
                        <div class="small">
                            <div class="row mb-2">
                                <div class="col-4 text-muted">Current Status:</div>
                                <div class="col-8">
                                    @if($registration->verification_status === 'verified')
                                        <span class="text-success">Verified</span>
                                    @else
                                        <span class="text-danger">Rejected</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4 text-muted">New Status:</div>
                                <div class="col-8"><span class="text-warning">Pending</span></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="resetReason{{ $registration->id }}" class="form-label">Reset Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="resetReason{{ $registration->id }}" name="notes" rows="3" placeholder="Please provide the reason for resetting the status..." required></textarea>
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
@endif
@endforeach

<!-- Advanced Filters Modal -->
<div class="modal fade" id="filtersModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Advanced Filters</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.registrations') }}" method="GET">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="filter-search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="filter-search" name="search" placeholder="Name, Omang, Email..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="filter-status" class="form-label">Verification Status</label>
                            <select class="form-select" id="filter-status" name="status">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="filter-date-from" class="form-label">Date From</label>
                            <input type="date" class="form-control" id="filter-date-from" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="filter-date-to" class="form-label">Date To</label>
                            <input type="date" class="form-control" id="filter-date-to" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="filter-sort" class="form-label">Sort By</label>
                            <select class="form-select" id="filter-sort" name="sort">
                                <option value="created_at" {{ request('sort') === 'created_at' || !request('sort') ? 'selected' : '' }}>Registration Date</option>
                                <option value="first_name" {{ request('sort') === 'first_name' ? 'selected' : '' }}>Name</option>
                                <option value="omang_number" {{ request('sort') === 'omang_number' ? 'selected' : '' }}>Omang Number</option>
                                <option value="verification_status" {{ request('sort') === 'verification_status' ? 'selected' : '' }}>Verification Status</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="filter-direction" class="form-label">Sort Direction</label>
                            <select class="form-select" id="filter-direction" name="direction">
                                <option value="desc" {{ request('direction') === 'desc' || !request('direction') ? 'selected' : '' }}>Descending</option>
                                <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>Ascending</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('admin.registrations') }}" class="btn btn-link">Reset Filters</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Refresh table button
    document.getElementById('refreshTable')?.addEventListener('click', function(e) {
        e.preventDefault();
        window.location.reload();
    });
    
    // Export functionality
    document.getElementById('exportTableCSV')?.addEventListener('click', function(e) {
        e.preventDefault();
        // Implement CSV export logic
        alert('Export as CSV functionality will be implemented here');
    });
    
    document.getElementById('exportTableExcel')?.addEventListener('click', function(e) {
        e.preventDefault();
        // Implement Excel export logic
        alert('Export as Excel functionality will be implemented here');
    });
    
    document.getElementById('printTable')?.addEventListener('click', function(e) {
        e.preventDefault();
        window.print();
    });
    
    document.getElementById('exportRegistrations')?.addEventListener('click', function(e) {
        e.preventDefault();
        // Implement export logic
        alert('Export registrations functionality will be implemented here');
    });
});
</script>
@endpush

@push('styles')
<style>
    @media print {
        .sidebar, .topbar, .app-footer, .card-header, .card-footer, .actions-column, .btn, button {
            display: none !important;
        }
        
        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        
        .card {
            box-shadow: none !important;
            border: none !important;
        }
        
        .content-wrapper {
            padding: 0 !important;
        }
    }
</style>
@endpush
@endsection