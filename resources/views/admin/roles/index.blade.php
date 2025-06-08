@extends('layouts.admin')

@section('title', 'Roles & Permissions')
@section('page_title', 'Roles & Permissions')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active" aria-current="page">Roles & Permissions</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Roles & Permissions</h2>
        <p class="text-muted">Manage user roles and their associated permissions</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
            <i class="fas fa-plus me-2"></i> Create New Role
        </button>
    </div>
</div>

<!-- Role Cards -->
<div class="row g-4 mb-4">
    <!-- Admin Role Card -->
    <div class="col-lg-4 col-md-6">
        <div class="card h-100 border-left-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-shield text-primary me-2"></i> Administrator
                </h5>
                <span class="badge bg-primary">System Role</span>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        <div class="avatar bg-primary-light text-primary">
                            <i class="fas fa-user-shield"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0">Admin</h6>
                        <p class="text-muted mb-0 small">Full system access and control</p>
                    </div>
                </div>
                
                <p class="mb-1"><strong>Active Users:</strong> 3</p>
                <div class="progress mb-3" style="height: 6px;">
                    <div class="progress-bar bg-primary" style="width: 100%;" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-muted fw-normal mb-2">Key Permissions</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-primary-light text-primary">All Permissions</span>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewRoleModal" data-role-id="1" data-role-name="Administrator">
                        <i class="fas fa-eye me-2"></i> View Details
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
                        <i class="fas fa-edit me-2"></i> Edit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bank Officer Role Card -->
    <div class="col-lg-4 col-md-6">
        <div class="card h-100 border-left-info">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-tie text-info me-2"></i> Bank Officer
                </h5>
                <span class="badge bg-info">System Role</span>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        <div class="avatar bg-info-light text-info">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0">Bank Officer</h6>
                        <p class="text-muted mb-0 small">Verification and customer management</p>
                    </div>
                </div>
                
                <p class="mb-1"><strong>Active Users:</strong> 8</p>
                <div class="progress mb-3" style="height: 6px;">
                    <div class="progress-bar bg-info" style="width: 75%;" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-muted fw-normal mb-2">Key Permissions</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-info-light text-info">Review Applications</span>
                        <span class="badge bg-info-light text-info">Verify Documents</span>
                        <span class="badge bg-info-light text-info">Manage Customers</span>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewRoleModal" data-role-id="2" data-role-name="Bank Officer">
                        <i class="fas fa-eye me-2"></i> View Details
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editRoleModal" data-role-id="2" data-role-name="Bank Officer">
                        <i class="fas fa-edit me-2"></i> Edit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Role Card -->
    <div class="col-lg-4 col-md-6">
        <div class="card h-100 border-left-success">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user text-success me-2"></i> Customer
                </h5>
                <span class="badge bg-success">System Role</span>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        <div class="avatar bg-success-light text-success">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0">Customer</h6>
                        <p class="text-muted mb-0 small">Regular banking customers</p>
                    </div>
                </div>
                
                <p class="mb-1"><strong>Active Users:</strong> 12,453</p>
                <div class="progress mb-3" style="height: 6px;">
                    <div class="progress-bar bg-success" style="width: 50%;" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-muted fw-normal mb-2">Key Permissions</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-success-light text-success">View Own Profile</span>
                        <span class="badge bg-success-light text-success">Update Personal Info</span>
                        <span class="badge bg-success-light text-success">Upload Documents</span>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewRoleModal" data-role-id="3" data-role-name="Customer">
                        <i class="fas fa-eye me-2"></i> View Details
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editRoleModal" data-role-id="3" data-role-name="Customer">
                        <i class="fas fa-edit me-2"></i> Edit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Supervisor Role Card -->
    <div class="col-lg-4 col-md-6">
        <div class="card h-100 border-left-warning">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-cog text-warning me-2"></i> Supervisor
                </h5>
                <span class="badge bg-warning text-dark">Custom Role</span>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        <div class="avatar bg-warning-light text-warning">
                            <i class="fas fa-user-cog"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="mb-0">Supervisor</h6>
                        <p class="text-muted mb-0 small">Banking officer supervision</p>
                    </div>
                </div>
                
                <p class="mb-1"><strong>Active Users:</strong> 4</p>
                <div class="progress mb-3" style="height: 6px;">
                    <div class="progress-bar bg-warning" style="width: 80%;" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-muted fw-normal mb-2">Key Permissions</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-warning-light text-warning">Manage Officers</span>
                        <span class="badge bg-warning-light text-warning">Approve Actions</span>
                        <span class="badge bg-warning-light text-warning">View Reports</span>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewRoleModal" data-role-id="4" data-role-name="Supervisor">
                        <i class="fas fa-eye me-2"></i> View Details
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editRoleModal" data-role-id="4" data-role-name="Supervisor">
                        <i class="fas fa-edit me-2"></i> Edit
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Permissions Management -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Permissions Management</h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-4">Define specific permissions that can be assigned to roles. Permissions determine what actions users can perform in the system.</p>
        
        <div class="table-responsive">
            <table class="data-table w-100">
                <thead>
                    <tr>
                        <th>Permission Name</th>
                        <th>Description</th>
                        <th>Module</th>
                        <th>Assigned To</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>manage_users</td>
                        <td>Create, update, and delete user accounts</td>
                        <td><span class="badge bg-primary">User Management</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <span class="badge bg-primary">Administrator</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPermissionModal" data-perm-id="1" data-perm-name="manage_users">
                                    <i class="fas fa-edit" data-bs-toggle="tooltip" title="Edit Permission"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deletePermissionModal" data-perm-id="1" data-perm-name="manage_users">
                                    <i class="fas fa-trash-alt" data-bs-toggle="tooltip" title="Delete Permission"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>review_applications</td>
                        <td>Review customer registration applications</td>
                        <td><span class="badge bg-info">Registration</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <span class="badge bg-primary">Administrator</span>
                                <span class="badge bg-info">Bank Officer</span>
                                <span class="badge bg-warning text-dark">Supervisor</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPermissionModal" data-perm-id="2" data-perm-name="review_applications">
                                    <i class="fas fa-edit" data-bs-toggle="tooltip" title="Edit Permission"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deletePermissionModal" data-perm-id="2" data-perm-name="review_applications">
                                    <i class="fas fa-trash-alt" data-bs-toggle="tooltip" title="Delete Permission"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>verify_documents</td>
                        <td>Verify customer submitted documents</td>
                        <td><span class="badge bg-info">Registration</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <span class="badge bg-primary">Administrator</span>
                                <span class="badge bg-info">Bank Officer</span>
                                <span class="badge bg-warning text-dark">Supervisor</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPermissionModal" data-perm-id="3" data-perm-name="verify_documents">
                                    <i class="fas fa-edit" data-bs-toggle="tooltip" title="Edit Permission"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deletePermissionModal" data-perm-id="3" data-perm-name="verify_documents">
                                    <i class="fas fa-trash-alt" data-bs-toggle="tooltip" title="Delete Permission"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>manage_settings</td>
                        <td>Manage system settings and configurations</td>
                        <td><span class="badge bg-secondary">System</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <span class="badge bg-primary">Administrator</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPermissionModal" data-perm-id="4" data-perm-name="manage_settings">
                                    <i class="fas fa-edit" data-bs-toggle="tooltip" title="Edit Permission"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deletePermissionModal" data-perm-id="4" data-perm-name="manage_settings">
                                    <i class="fas fa-trash-alt" data-bs-toggle="tooltip" title="Delete Permission"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>view_reports</td>
                        <td>View system reports and analytics</td>
                        <td><span class="badge bg-secondary">System</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <span class="badge bg-primary">Administrator</span>
                                <span class="badge bg-warning text-dark">Supervisor</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPermissionModal" data-perm-id="5" data-perm-name="view_reports">
                                    <i class="fas fa-edit" data-bs-toggle="tooltip" title="Edit Permission"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deletePermissionModal" data-perm-id="5" data-perm-name="view_reports">
                                    <i class="fas fa-trash-alt" data-bs-toggle="tooltip" title="Delete Permission"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>manage_officers</td>
                        <td>Manage bank officers and their activities</td>
                        <td><span class="badge bg-warning text-dark">Officer Management</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <span class="badge bg-primary">Administrator</span>
                                <span class="badge bg-warning text-dark">Supervisor</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPermissionModal" data-perm-id="6" data-perm-name="manage_officers">
                                    <i class="fas fa-edit" data-bs-toggle="tooltip" title="Edit Permission"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deletePermissionModal" data-perm-id="6" data-perm-name="manage_officers">
                                    <i class="fas fa-trash-alt" data-bs-toggle="tooltip" title="Delete Permission"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="text-end mt-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                <i class="fas fa-plus me-2"></i> Add Permission
            </button>
        </div>
    </div>
</div>

<!-- Modals -->
<!-- View Role Modal -->
<div class="modal fade" id="viewRoleModal" tabindex="-1" aria-labelledby="viewRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewRoleModalLabel">Role Details: <span id="viewRoleTitle">Administrator</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card h-100 bg-light">
                            <div class="card-body">
                                <h6 class="card-title mb-3">Role Information</h6>
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Role Name</label>
                                    <div class="fw-medium">Administrator</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Role Type</label>
                                    <div class="fw-medium">System Role</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Created</label>
                                    <div class="fw-medium">Jan 15, 2023</div>
                                </div>
                                <div>
                                    <label class="form-label text-muted small mb-1">Active Users</label>
                                    <div class="fw-medium">3</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card h-100 bg-light">
                            <div class="card-body">
                                <h6 class="card-title mb-3">Permissions</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Permission</th>
                                                <th>Description</th>
                                                <th>Module</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>manage_users</td>
                                                <td>Create, update, and delete user accounts</td>
                                                <td><span class="badge bg-primary">User Management</span></td>
                                            </tr>
                                            <tr>
                                                <td>review_applications</td>
                                                <td>Review customer registration applications</td>
                                                <td><span class="badge bg-info">Registration</span></td>
                                            </tr>
                                            <tr>
                                                <td>verify_documents</td>
                                                <td>Verify customer submitted documents</td>
                                                <td><span class="badge bg-info">Registration</span></td>
                                            </tr>
                                            <tr>
                                                <td>manage_settings</td>
                                                <td>Manage system settings and configurations</td>
                                                <td><span class="badge bg-secondary">System</span></td>
                                            </tr>
                                            <tr>
                                                <td>view_reports</td>
                                                <td>View system reports and analytics</td>
                                                <td><span class="badge bg-secondary">System</span></td>
                                            </tr>
                                            <tr>
                                                <td>manage_officers</td>
                                                <td>Manage bank officers and their activities</td>
                                                <td><span class="badge bg-warning text-dark">Officer Management</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="editPermissionForm" class="btn btn-primary">Update Permission</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Permission Modal -->
<div class="modal fade" id="deletePermissionModal" tabindex="-1" aria-labelledby="deletePermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePermissionModalLabel">Delete Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> Deleting a permission will remove it from all roles. This action cannot be undone.
                </div>
                <p>Are you sure you want to delete the permission <strong id="deletePermName">manage_users</strong>?</p>
                <form id="deletePermissionForm" action="#" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="delete_perm_id" name="permission_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="deletePermissionForm" class="btn btn-danger">Delete Permission</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    
    // Handle select all permissions checkbox
    document.getElementById('select_all_permissions')?.addEventListener('change', function() {
        const permissionChecks = document.querySelectorAll('.permission-check');
        permissionChecks.forEach(check => {
            check.checked = this.checked;
        });
    });
    
    document.getElementById('edit_select_all_permissions')?.addEventListener('change', function() {
        const permissionChecks = document.querySelectorAll('.edit-permission-check');
        permissionChecks.forEach(check => {
            check.checked = this.checked;
        });
    });
    
    // Handle view role modal
    const viewRoleModal = document.getElementById('viewRoleModal');
    if (viewRoleModal) {
        viewRoleModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const roleId = button.dataset.roleId;
            const roleName = button.dataset.roleName;
            
            document.getElementById('viewRoleTitle').textContent = roleName;
            
            // Update edit button data attributes
            const editRoleBtn = document.getElementById('editRoleBtn');
            editRoleBtn.dataset.roleId = roleId;
            editRoleBtn.dataset.roleName = roleName;
            
            // If role is Administrator, disable edit button
            if (roleName === 'Administrator') {
                editRoleBtn.disabled = true;
            } else {
                editRoleBtn.disabled = false;
            }
        });
    }
    
    // Handle edit role button in view role modal
    document.getElementById('editRoleBtn')?.addEventListener('click', function() {
        const roleId = this.dataset.roleId;
        const roleName = this.dataset.roleName;
        
        // Close the view modal
        bootstrap.Modal.getInstance(viewRoleModal).hide();
        
        // Open the edit modal
        const editRoleModal = new bootstrap.Modal(document.getElementById('editRoleModal'));
        
        // Set the role name in the edit modal
        document.getElementById('editRoleTitle').textContent = roleName;
        document.getElementById('edit_role_id').value = roleId;
        
        // Show the edit modal
        editRoleModal.show();
    });
    
    // Handle edit role modal
    const editRoleModal = document.getElementById('editRoleModal');
    if (editRoleModal) {
        editRoleModal.addEventListener('show.bs.modal', function(event) {
            if (event.relatedTarget) { // Only if triggered by a button, not by JS
                const button = event.relatedTarget;
                const roleId = button.dataset.roleId;
                const roleName = button.dataset.roleName;
                
                document.getElementById('editRoleTitle').textContent = roleName;
                document.getElementById('edit_role_id').value = roleId;
                document.getElementById('edit_role_name').value = roleName;
                
                // Here you would normally load the role details via AJAX
                // For demo purposes, we're just setting some predefined values
                if (roleId == '2') { // Bank Officer
                    document.getElementById('edit_role_description').value = 'Banking officers responsible for customer verification and management.';
                    document.getElementById('edit_perm_manage_users').checked = false;
                    document.getElementById('edit_perm_review_applications').checked = true;
                    document.getElementById('edit_perm_verify_documents').checked = true;
                    document.getElementById('edit_perm_manage_settings').checked = false;
                    document.getElementById('edit_perm_view_reports').checked = false;
                    document.getElementById('edit_perm_manage_officers').checked = false;
                } else if (roleId == '3') { // Customer
                    document.getElementById('edit_role_description').value = 'Regular banking customers with limited system access.';
                    document.getElementById('edit_perm_manage_users').checked = false;
                    document.getElementById('edit_perm_review_applications').checked = false;
                    document.getElementById('edit_perm_verify_documents').checked = false;
                    document.getElementById('edit_perm_manage_settings').checked = false;
                    document.getElementById('edit_perm_view_reports').checked = false;
                    document.getElementById('edit_perm_manage_officers').checked = false;
                } else if (roleId == '4') { // Supervisor
                    document.getElementById('edit_role_description').value = 'Banking supervisors with officer management capabilities.';
                    document.getElementById('edit_perm_manage_users').checked = false;
                    document.getElementById('edit_perm_review_applications').checked = true;
                    document.getElementById('edit_perm_verify_documents').checked = true;
                    document.getElementById('edit_perm_manage_settings').checked = false;
                    document.getElementById('edit_perm_view_reports').checked = true;
                    document.getElementById('edit_perm_manage_officers').checked = true;
                }
            }
        });
    }
    
    // Handle edit permission modal
    const editPermissionModal = document.getElementById('editPermissionModal');
    if (editPermissionModal) {
        editPermissionModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const permId = button.dataset.permId;
            const permName = button.dataset.permName;
            
            document.getElementById('editPermTitle').textContent = permName;
            document.getElementById('edit_perm_id').value = permId;
            document.getElementById('edit_permission_name').value = permName;
            
            // Here you would normally load the permission details via AJAX
            // For demo purposes, we're just setting some predefined values
            if (permName === 'manage_users') {
                document.getElementById('edit_permission_description').value = 'Create, update, and delete user accounts';
                document.getElementById('edit_permission_module').value = 'User Management';
                document.getElementById('edit_assign_admin').checked = true;
                document.getElementById('edit_assign_officer').checked = false;
                document.getElementById('edit_assign_customer').checked = false;
                document.getElementById('edit_assign_supervisor').checked = false;
            } else if (permName === 'review_applications') {
                document.getElementById('edit_permission_description').value = 'Review customer registration applications';
                document.getElementById('edit_permission_module').value = 'Registration';
                document.getElementById('edit_assign_admin').checked = true;
                document.getElementById('edit_assign_officer').checked = true;
                document.getElementById('edit_assign_customer').checked = false;
                document.getElementById('edit_assign_supervisor').checked = true;
            }
        });
    }
    
    // Handle delete permission modal
    const deletePermissionModal = document.getElementById('deletePermissionModal');
    if (deletePermissionModal) {
        deletePermissionModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const permId = button.dataset.permId;
            const permName = button.dataset.permName;
            
            document.getElementById('deletePermName').textContent = permName;
            document.getElementById('delete_perm_id').value = permId;
        });
    }
});
</script>
@endpush
@endsection