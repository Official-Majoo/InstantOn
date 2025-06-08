@extends('layouts.admin')

@section('title', 'Reports')
@section('page_title', 'Reports')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
<li class="breadcrumb-item active" aria-current="page">Reports</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Reports</h2>
        <p class="text-muted">Generate and view system reports.</p>
    </div>
</div>

<!-- Generate Report Card -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title">Generate New Report</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.reports.generate') }}" method="POST" class="row g-3">
            @csrf
            
            <div class="col-md-6">
                <label for="report_type" class="form-label">Report Type <span class="text-danger">*</span></label>
                <select class="form-select" id="report_type" name="report_type" required>
                    <option value="" selected disabled>Select report type</option>
                    <option value="registrations">Customer Registrations Report</option>
                    <option value="verifications">Verification Activity Report</option>
                    <option value="officer_performance">Bank Officer Performance Report</option>
                    <option value="system_activity">System Activity Report</option>
                </select>
            </div>
            
            <div class="col-md-6">
                <label for="format" class="form-label">Report Format <span class="text-danger">*</span></label>
                <select class="form-select" id="format" name="format" required>
                    <option value="pdf">PDF Document</option>
                    <option value="excel">Excel Spreadsheet</option>
                    <option value="csv">CSV File</option>
                </select>
            </div>
            
            <div class="col-md-6">
                <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="start_date" name="start_date" required value="{{ now()->subMonth()->format('Y-m-d') }}">
            </div>
            
            <div class="col-md-6">
                <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="end_date" name="end_date" required value="{{ now()->format('Y-m-d') }}">
            </div>
            
            <div class="col-12" id="reportOptions">
                <!-- Dynamic options based on report type will be added here -->
            </div>
            
            <div class="col-12">
                <div class="alert alert-info">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div>
                            <h6 class="alert-heading mb-1">Report Generation Information</h6>
                            <p class="mb-0">
                                Report generation may take a few moments. Once complete, you will receive a notification 
                                and the report will be available for download in the Recent Reports section.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-file-alt me-2"></i> Generate Report
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Recent Reports Card -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Recent Reports</h5>
        <div>
            <button type="button" class="btn btn-sm btn-light" id="refreshReports">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="data-table w-100">
                <thead>
                    <tr>
                        <th style="width: 25%">Report Name</th>
                        <th style="width: 15%">Type</th>
                        <th style="width: 15%">Format</th>
                        <th style="width: 20%">Date Range</th>
                        <th style="width: 15%">Generated</th>
                        <th style="width: 10%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Sample data - replace with actual reports -->
                    <tr>
                        <td>Monthly Registration Report</td>
                        <td>
                            <span class="badge badge-primary">Registrations</span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-file-pdf text-danger me-1"></i> PDF
                            </span>
                        </td>
                        <td>01 May 2023 - 31 May 2023</td>
                        <td>03 Jun 2023, 14:25</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Download Report">
                                    <i class="fas fa-download"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete Report">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Officer Performance Q1</td>
                        <td>
                            <span class="badge badge-info">Officer Performance</span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-file-excel text-success me-1"></i> Excel
                            </span>
                        </td>
                        <td>01 Jan 2023 - 31 Mar 2023</td>
                        <td>05 Apr 2023, 09:12</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Download Report">
                                    <i class="fas fa-download"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete Report">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>System Activity Audit</td>
                        <td>
                            <span class="badge badge-secondary">System Activity</span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-file-csv text-primary me-1"></i> CSV
                            </span>
                        </td>
                        <td>01 Apr 2023 - 30 Apr 2023</td>
                        <td>02 May 2023, 16:40</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Download Report">
                                    <i class="fas fa-download"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete Report">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Report Templates Card -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title">Saved Report Templates</h5>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <!-- Registration Summary Template -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle icon-primary me-3" style="width: 48px; height: 48px;">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Registration Summary</h6>
                                <p class="text-muted mb-0 small">Monthly registration statistics</p>
                            </div>
                        </div>
                        <div class="small text-muted mb-3">
                            <div><strong>Type:</strong> Registrations Report</div>
                            <div><strong>Format:</strong> PDF</div>
                            <div><strong>Period:</strong> Previous Month</div>
                        </div>
                        <div class="d-grid">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="runReportTemplate('registration_summary')">
                                <i class="fas fa-play me-2"></i> Run Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Officer Performance Template -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle icon-info me-3" style="width: 48px; height: 48px;">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Officer Performance</h6>
                                <p class="text-muted mb-0 small">Bank officer activity metrics</p>
                            </div>
                        </div>
                        <div class="small text-muted mb-3">
                            <div><strong>Type:</strong> Officer Performance Report</div>
                            <div><strong>Format:</strong> Excel</div>
                            <div><strong>Period:</strong> Current Quarter</div>
                        </div>
                        <div class="d-grid">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="runReportTemplate('officer_performance')">
                                <i class="fas fa-play me-2"></i> Run Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- System Audit Template -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle icon-secondary me-3" style="width: 48px; height: 48px;">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">System Audit</h6>
                                <p class="text-muted mb-0 small">Complete system activity log</p>
                            </div>
                        </div>
                        <div class="small text-muted mb-3">
                            <div><strong>Type:</strong> System Activity Report</div>
                            <div><strong>Format:</strong> CSV</div>
                            <div><strong>Period:</strong> Previous Week</div>
                        </div>
                        <div class="d-grid">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="runReportTemplate('system_audit')">
                                <i class="fas fa-play me-2"></i> Run Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dynamic form options based on report type
    const reportTypeSelect = document.getElementById('report_type');
    const reportOptionsDiv = document.getElementById('reportOptions');
    
    reportTypeSelect?.addEventListener('change', function() {
        const reportType = this.value;
        
        // Clear existing options
        reportOptionsDiv.innerHTML = '';
        
        // Add options based on report type
        if (reportType === 'registrations') {
            reportOptionsDiv.innerHTML = `
                <div class="mb-3">
                    <label class="form-label">Registration Status</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="include_verified" name="include_verified" value="1" checked>
                            <label class="form-check-label" for="include_verified">Verified</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="include_pending" name="include_pending" value="1" checked>
                            <label class="form-check-label" for="include_pending">Pending</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="include_rejected" name="include_rejected" value="1" checked>
                            <label class="form-check-label" for="include_rejected">Rejected</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="include_charts" name="include_charts" value="1" checked>
                        <label class="form-check-label" for="include_charts">Include visual charts and graphs</label>
                    </div>
                </div>
            `;
        } else if (reportType === 'officer_performance') {
            reportOptionsDiv.innerHTML = `
                <div class="mb-3">
                    <label for="officer_id" class="form-label">Select Officer</label>
                    <select class="form-select" id="officer_id" name="officer_id">
                        <option value="">All Officers</option>
                        <!-- Add officers dynamically -->
                        <option value="1">John Doe</option>
                        <option value="2">Jane Smith</option>
                    </select>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="include_efficiency_metrics" name="include_efficiency_metrics" value="1" checked>
                        <label class="form-check-label" for="include_efficiency_metrics">Include efficiency metrics</label>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="include_comparison" name="include_comparison" value="1" checked>
                        <label class="form-check-label" for="include_comparison">Include comparison with average performance</label>
                    </div>
                </div>
            `;
        } else if (reportType === 'system_activity') {
            reportOptionsDiv.innerHTML = `
                <div class="mb-3">
                    <label for="activity_type" class="form-label">Activity Type</label>
                    <select class="form-select" id="activity_type" name="activity_type">
                        <option value="">All Activities</option>
                        <option value="user_management">User Management</option>
                        <option value="verification">Verification</option>
                        <option value="login">Login/Logout</option>
                        <option value="document">Document Management</option>
                    </select>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="include_user_details" name="include_user_details" value="1" checked>
                        <label class="form-check-label" for="include_user_details">Include detailed user information</label>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="include_ip_addresses" name="include_ip_addresses" value="1" checked>
                        <label class="form-check-label" for="include_ip_addresses">Include IP addresses</label>
                    </div>
                </div>
            `;
        }
    });
    
    // Refresh reports list
    document.getElementById('refreshReports')?.addEventListener('click', function(e) {
        e.preventDefault();
        // Implement refresh logic
        alert('Reports list refreshed');
    });
    
    // Date range validation
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    endDateInput?.addEventListener('change', function() {
        if (startDateInput.value && this.value && new Date(this.value) < new Date(startDateInput.value)) {
            alert('End date cannot be earlier than start date');
            this.value = '';
        }
    });
    
    startDateInput?.addEventListener('change', function() {
        if (endDateInput.value && this.value && new Date(this.value) > new Date(endDateInput.value)) {
            alert('Start date cannot be later than end date');
            this.value = '';
        }
    });
});

// Run report template function
function runReportTemplate(templateId) {
    // Implement template execution logic
    alert(`Generating report using template: ${templateId}`);
    
    // Simulate processing
    const loadingToast = document.createElement('div');
    loadingToast.className = 'position-fixed bottom-0 end-0 p-3';
    loadingToast.style.zIndex = '5';
    loadingToast.innerHTML = `
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Report Generation</strong>
                <button type="button" class="btn-close" onclick="this.parentNode.parentNode.parentNode.remove()"></button>
            </div>
            <div class="toast-body">
                <div class="d-flex align-items-center">
                    <div class="spinner-border spinner-border-sm me-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div>Generating report... Please wait.</div>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(loadingToast);
    
    // Simulate completion after 3 seconds
    setTimeout(function() {
        loadingToast.remove();
        
        const successToast = document.createElement('div');
        successToast.className = 'position-fixed bottom-0 end-0 p-3';
        successToast.style.zIndex = '5';
        successToast.innerHTML = `
            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header bg-success text-white">
                    <strong class="me-auto">Report Complete</strong>
                    <button type="button" class="btn-close btn-close-white" onclick="this.parentNode.parentNode.parentNode.remove()"></button>
                </div>
                <div class="toast-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <div>Report generated successfully. <a href="#" class="text-primary">Download now</a>.</div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(successToast);
        
        // Auto-remove after 5 seconds
        setTimeout(function() {
            successToast.remove();
        }, 5000);
    }, 3000);
}
</script>
@endpush
@endsection