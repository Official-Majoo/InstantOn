@extends('layouts.admin')

@section('title', 'Bank Officers')
@section('page_title', 'Bank Officers')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active" aria-current="page">Bank Officers</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Bank Officers Management</h2>
        <p class="text-muted">Manage bank officers who review and approve customer registrations.</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOfficerModal">
            <i class="fas fa-user-plus me-2"></i> Add Bank Officer
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon icon-info">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Officers</div>
                <div class="stat-value">{{ number_format($officers->total()) }}</div>
                <div class="stat-change">
                    <span class="text-muted">Active personnel</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon icon-success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Approvals</div>
                <div class="stat-value">{{ number_format($officers->sum('approved_count')) }}</div>
                <div class="stat-change">
                    <span class="text-success">Verified customers</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon icon-danger">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Rejections</div>
                <div class="stat-value">{{ number_format($officers->sum('rejected_count')) }}</div>
                <div class="stat-change">
                    <span class="text-danger">Rejected applications</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon icon-primary">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Efficiency Rate</div>
                <div class="stat-value">
                    @php
                        $totalReviews = $officers->sum('reviews_count');
                        $efficiencyRate = $totalReviews > 0 
                            ? round(($officers->sum('approved_count') / $totalReviews) * 100) 
                            : 0;
                    @endphp
                    {{ $efficiencyRate }}%
                </div>
                <div class="stat-change">
                    <span class="text-primary">Approval rate</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Officers Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Bank Officers</h5>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-light" id="refreshTable">
                <i class="fas fa-sync-alt"></i>
            </button>
            <div class="dropdown">
                <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-file-export"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#" id="exportCSV">
                        <i class="fas fa-file-csv me-2"></i> Export as CSV
                    </a></li>
                    <li><a class="dropdown-item" href="#" id="exportExcel">
                        <i class="fas fa-file-excel me-2"></i> Export as Excel
                    </a></li>
                    <li><a class="dropdown-item" href="#" id="exportPDF">
                        <i class="fas fa-file-pdf me-2"></i> Export as PDF
                    </a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="data-table w-100">
                <thead>
                    <tr>
                        <th style="width: 35%">Officer</th>
                        <th style="width: 15%">Status</th>
                        <th style="width: 15%">Reviews</th>
                        <th style="width: 20%">Performance</th>
                        <th style="width: 15%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($officers as $officer)
                    <tr>
                        <td>
                            <div class="user-card">
                                <div class="avatar">
                                    @if($officer->profile_photo_path)
                                    <img src="{{ asset($officer->profile_photo_path) }}" alt="{{ $officer->name }}">
                                    @else
                                    {{ substr($officer->name, 0, 1) }}
                                    @endif
                                </div>
                                <div class="user-info">
                                    <h6 class="user-name">{{ $officer->name }}</h6>
                                    <p class="user-email">
                                        {{ $officer->email }}
                                        @if($officer->phone_number)
                                        <span class="d-block">{{ $officer->phone_number }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($officer->status === 'active')
                                <span class="status-indicator status-verified">
                                    <i class="fas fa-check-circle"></i> Active
                                </span>
                            @else
                                <span class="status-indicator" style="background-color: #e9ecef; color: #6c757d;">
                                    <i class="fas fa-ban"></i> Inactive
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <div class="mb-1">
                                    <span class="fw-medium">{{ number_format($officer->reviews_count) }}</span> total
                                </div>
                                <div class="small d-flex gap-3">
                                    <span class="text-success">
                                        <i class="fas fa-check-circle"></i> {{ number_format($officer->approved_count) }}
                                    </span>
                                    <span class="text-danger">
                                        <i class="fas fa-times-circle"></i> {{ number_format($officer->rejected_count) }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $totalReviews = $officer->reviews_count;
                                $approvalRate = $totalReviews > 0 
                                    ? round(($officer->approved_count / $totalReviews) * 100) 
                                    : 0;
                            @endphp
                            <div class="mb-1 small">Approval Rate: <span class="fw-medium">{{ $approvalRate }}%</span></div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-{{ $approvalRate >= 80 ? 'success' : ($approvalRate >= 50 ? 'warning' : 'danger') }}" 
                                    role="progressbar" 
                                    style="width: {{ $approvalRate }}%;" 
                                    aria-valuenow="{{ $approvalRate }}" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.users.edit', $officer->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit Officer">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewReviewsModal{{ $officer->id }}" title="View Reviews">
                                    <i class="fas fa-clipboard-list"></i>
                                </button>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('admin.users.edit', $officer->id) }}">
                                            <i class="fas fa-edit me-2 text-primary"></i> Edit Officer
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#viewReviewsModal{{ $officer->id }}">
                                            <i class="fas fa-clipboard-list me-2 text-info"></i> View Reviews
                                        </a></li>
                                        <li><a class="dropdown-item" href="#">
                                            <i class="fas fa-key me-2 text-warning"></i> Reset Password
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteOfficerModal{{ $officer->id }}">
                                            <i class="fas fa-trash-alt me-2"></i> Delete Officer
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">No bank officers found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-transparent">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Showing {{ $officers->firstItem() ?? 0 }} to {{ $officers->lastItem() ?? 0 }} of {{ $officers->total() }} officers
            </div>
            <div>
                {{ $officers->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add Bank Officer Modal -->
<div class="modal fade" id="addOfficerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Bank Officer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <input type="hidden" name="role" value="bank_officer">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number">
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="form-text">Password must be at least 8 characters long.</div>
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
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="send_welcome_email" name="send_welcome_email" checked>
                        <label class="form-check-label" for="send_welcome_email">
                            Send welcome email with login instructions
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Officer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reviews Modals -->
@foreach($officers as $officer)
<div class="modal fade" id="viewReviewsModal{{ $officer->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Officer Reviews - {{ $officer->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="mb-0">Review History</h6>
                        <p class="text-muted small mb-0">Recent verification reviews by this officer</p>
                    </div>
                    <div>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary active" data-filter="all">All</button>
                            <button type="button" class="btn btn-outline-success" data-filter="verified">Verified</button>
                            <button type="button" class="btn btn-outline-danger" data-filter="rejected">Rejected</button>
                        </div>
                    </div>
                </div>
                
                <div class="timeline">
                    @forelse($officer->reviews->take(10) as $review)
                    <div class="timeline-item" data-status="{{ $review->status }}">
                        <div class="timeline-icon {{ $review->status === 'verified' ? 'success' : 'danger' }}"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-medium">{{ $review->customerProfile->full_name }}</span>
                                <span class="timeline-time">{{ $review->review_timestamp->format('d M Y, H:i') }}</span>
                            </div>
                            <p class="mb-1">
                                Status: <span class="fw-medium {{ $review->status === 'verified' ? 'text-success' : 'text-danger' }}">{{ ucfirst($review->status) }}</span>
                            </p>
                            @if($review->notes)
                            <div class="small text-muted mt-1">
                                "{{ $review->notes }}"
                            </div>
                            @endif
                            <div class="mt-2">
                                <a href="{{ route('admin.customer.view', $review->customer_profile_id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i> View Customer
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <div class="icon-circle icon-secondary mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <h6>No Reviews Found</h6>
                        <p class="text-muted small">This officer has not reviewed any registrations yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Officer Modal -->
<div class="modal fade" id="deleteOfficerModal{{ $officer->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem; background-color: #FEE2E2; color: #EF4444;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h5>Are you sure you want to delete this bank officer?</h5>
                    <p class="text-muted">This action cannot be undone. The officer will lose access to the system.</p>
                </div>
                
                <div class="border rounded p-3 mb-3 bg-light">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            @if($officer->profile_photo_path)
                            <img src="{{ asset($officer->profile_photo_path) }}" alt="{{ $officer->name }}">
                            @else
                            {{ substr($officer->name, 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $officer->name }}</h6>
                            <p class="text-muted mb-0">{{ $officer->email }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-warning">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div>
                            <p class="mb-0">Note: This officer has reviewed {{ number_format($officer->reviews_count) }} customer registrations. These records will be preserved for audit purposes.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.users.delete', $officer->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Officer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    
    if (togglePassword && password) {
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }
    
    // Toggle confirm password visibility
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const passwordConfirmation = document.getElementById('password_confirmation');
    
    if (toggleConfirmPassword && passwordConfirmation) {
        toggleConfirmPassword.addEventListener('click', function() {
            const type = passwordConfirmation.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmation.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }
    
    // Filter reviews in modal
    document.querySelectorAll('[data-filter]').forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            const modal = this.closest('.modal');
            
            // Update active button
            modal.querySelectorAll('[data-filter]').forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');
            
            // Filter timeline items
            const items = modal.querySelectorAll('.timeline-item');
            items.forEach(item => {
                if (filter === 'all' || item.dataset.status === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
    
    // Refresh table
    document.getElementById('refreshTable')?.addEventListener('click', function(e) {
        e.preventDefault();
        window.location.reload();
    });
    
    // Export functionality
    document.getElementById('exportCSV')?.addEventListener('click', function(e) {
        e.preventDefault();
        // Implement CSV export logic
        alert('Export as CSV functionality will be implemented here');
    });
    
    document.getElementById('exportExcel')?.addEventListener('click', function(e) {
        e.preventDefault();
        // Implement Excel export logic
        alert('Export as Excel functionality will be implemented here');
    });
    
    document.getElementById('exportPDF')?.addEventListener('click', function(e) {
        e.preventDefault();
        // Implement PDF export logic
        alert('Export as PDF functionality will be implemented here');
    });
});
</script>
@endpush
@endsection