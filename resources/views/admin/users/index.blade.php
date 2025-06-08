@extends('layouts.admin')

@section('title', 'User Management')
@section('page_title', 'User Management')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active" aria-current="page">Users</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">User Management</h2>
        <p class="text-muted">Manage and monitor all system users.</p>
    </div>
    <div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus me-2"></i> Add New User
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title">Filter Users</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.users') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Search</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="search" name="search" placeholder="Name, email, phone..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="bank_officer" {{ request('role') == 'bank_officer' ? 'selected' : '' }}>Bank Officer</option>
                    <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <div class="d-grid gap-2 d-md-flex w-100">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-filter me-2"></i> Apply Filters
                    </button>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">All Users</h5>
        <div class="dropdown">
            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-ellipsis-v"></i>
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
                        <th style="width: 40%">User</th>
                        <th style="width: 15%">Role</th>
                        <th style="width: 15%">Status</th>
                        <th style="width: 15%">Created</th>
                        <th style="width: 15%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="user-card">
                                <div class="avatar">
                                    @if($user->profile_photo_path)
                                    <img src="{{ asset($user->profile_photo_path) }}" alt="{{ $user->name }}">
                                    @else
                                    {{ substr($user->name, 0, 1) }}
                                    @endif
                                </div>
                                <div class="user-info">
                                    <h6 class="user-name">{{ $user->name }}</h6>
                                    <p class="user-email">
                                        {{ $user->email }}
                                        @if($user->phone_number)
                                        <span class="d-block">{{ $user->phone_number }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge bg-primary">Admin</span>
                            @elseif($user->role === 'bank_officer')
                                <span class="badge bg-info">Bank Officer</span>
                            @else
                                <span class="badge bg-secondary">Customer</span>
                            @endif
                        </td>
                        <td>
                            @if($user->status === 'active')
                                <span class="status-indicator status-verified">
                                    <i class="fas fa-check-circle"></i> Active
                                </span>
                            @elseif($user->status === 'inactive')
                                <span class="status-indicator" style="background-color: #e9ecef; color: #6c757d;">
                                    <i class="fas fa-ban"></i> Inactive
                                </span>
                            @elseif($user->status === 'pending')
                                <span class="status-indicator status-pending">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                            @else
                                <span class="status-indicator status-rejected">
                                    <i class="fas fa-times-circle"></i> Rejected
                                </span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit User">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id !== Auth::id())
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}" title="Delete User">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                @endif
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('admin.users.edit', $user->id) }}">
                                            <i class="fas fa-edit me-2 text-primary"></i> Edit User
                                        </a></li>
                                        @if($user->role === 'customer')
                                        <li><a class="dropdown-item" href="{{ route('admin.customer.view', $user->customerProfile->id ?? 0) }}">
                                            <i class="fas fa-id-card me-2 text-info"></i> View Profile
                                        </a></li>
                                        @endif
                                        <li><a class="dropdown-item" href="#">
                                            <i class="fas fa-key me-2 text-warning"></i> Reset Password
                                        </a></li>
                                        @if($user->id !== Auth::id())
                                        <li><hr class="dropdown-divider"></li>
                                        <li><button class="dropdown-item text-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                                            <i class="fas fa-trash-alt me-2"></i> Delete User
                                        </button></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">No users found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-transparent">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Delete User Modals -->
@foreach($users as $user)
@if($user->id !== Auth::id())
<div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
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
                    <h5>Are you sure you want to delete this user?</h5>
                    <p class="text-muted">This action cannot be undone. All data associated with this user will be permanently deleted.</p>
                </div>
                
                <div class="border rounded p-3 mb-3 bg-light">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            @if($user->profile_photo_path)
                            <img src="{{ asset($user->profile_photo_path) }}" alt="{{ $user->name }}">
                            @else
                            {{ substr($user->name, 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $user->name }}</h6>
                            <p class="text-muted mb-0">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
    
    document.getElementById('printTable')?.addEventListener('click', function(e) {
        e.preventDefault();
        window.print();
    });
});
</script>
@endpush

@push('styles')
<style>
    @media print {
        .sidebar, .topbar, .app-footer, .card-header, .card-footer, .actions-column {
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