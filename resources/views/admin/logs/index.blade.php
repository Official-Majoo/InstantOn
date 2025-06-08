@extends('layouts.admin')

@section('title', 'Activity Logs')
@section('page_title', 'Activity Logs')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Activity Logs</li>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Activity Logs</h2>
            <p class="text-muted">Monitor system activities and user actions.</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary" id="refreshLogs">
                <i class="fas fa-sync-alt me-2"></i> Refresh
            </button>
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fas fa-download me-2"></i> Export
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

    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title">Filter Logs</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.logs') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="type" class="form-label">Log Type</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">All Types</option>
                        @foreach ($logTypes as $logType)
                            <option value="{{ $logType }}" {{ request('type') == $logType ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $logType)) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="causer_id" class="form-label">User</label>
                    <select class="form-select" id="causer_id" name="causer_id">
                        <option value="">All Users</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ request('causer_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date"
                        value="{{ request('start_date') }}">
                </div>

                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date"
                        value="{{ request('end_date') }}">
                </div>

                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.logs') }}" class="btn btn-light">Reset Filters</a>
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Activity Log Timeline -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Activity Timeline</h5>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center">
                    <span class="me-2 text-muted small">Show:</span>
                    <select class="form-select form-select-sm" id="pageSize" style="width: 80px;">
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-secondary active" data-view="timeline">
                        <i class="fas fa-stream"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-view="table">
                        <i class="fas fa-table"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <div id="timelineView">
                <div class="timeline">
                    @forelse($activities as $date => $dateActivities)
                        <div class="date-separator mb-4">
                            <div class="date-label">{{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</div>
                        </div>

                        @foreach ($dateActivities as $activity)
                            <div class="timeline-item">
                                @php
                                    $iconClass = 'secondary';
                                    // Check if $activity is an object (not a boolean) before accessing properties
                                    if (
                                        is_object($activity) &&
                                        property_exists($activity, 'log_name') &&
                                        $activity->log_name
                                    ) {
                                        if (str_contains($activity->log_name, 'user')) {
                                            $iconClass = 'primary';
                                        } elseif (
                                            str_contains($activity->log_name, 'verification') ||
                                            str_contains($activity->log_name, 'verified')
                                        ) {
                                            $iconClass = 'success';
                                        } elseif (
                                            str_contains($activity->log_name, 'reject') ||
                                            str_contains($activity->log_name, 'delete')
                                        ) {
                                            $iconClass = 'danger';
                                        } elseif (str_contains($activity->log_name, 'login')) {
                                            $iconClass = 'info';
                                        }
                                    }
                                @endphp
                                <div class="timeline-icon {{ $iconClass }}"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span
                                            class="fw-medium">{{ is_object($activity) && isset($activity->causer) ? $activity->causer->name ?? 'System' : 'System' }}</span>
                                        <span
                                            class="timeline-time">{{ is_object($activity) && method_exists($activity, 'created_at') ? $activity->created_at->format('H:i') : '' }}</span>
                                    </div>
                                    <p class="mb-1">{{ is_object($activity) ? $activity->description ?? '' : '' }}</p>

                                    @if (is_object($activity) &&
                                            isset($activity->properties) &&
                                            !empty($activity->properties) &&
                                            is_array($activity->properties))
                                        <div class="small text-muted">
                                            @foreach ($activity->properties as $key => $value)
                                                @if (!is_array($value) && $key !== 'attributes' && $key !== 'old')
                                                    <div><strong>{{ ucfirst($key) }}:</strong> {{ $value }}</div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif

                                    @if (is_object($activity) && isset($activity->subject) && $activity->subject)
                                        <div class="mt-2">
                                            @if (isset($activity->subject_type) && $activity->subject_type === 'App\Models\CustomerProfile')
                                                <a href="{{ route('admin.customer.view', $activity->subject_id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i> View Customer
                                                </a>
                                            @elseif(isset($activity->subject_type) && $activity->subject_type === 'App\Models\User')
                                                <a href="{{ route('admin.users.edit', $activity->subject_id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-user me-1"></i> View User
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                    @empty
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-history text-muted fa-3x"></i>
                            </div>
                            <p class="text-muted">No activity logs found</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div id="tableView" style="display: none;">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>User</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Subject</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activities->flatten() as $activity)
                                <tr>
                                    <td>{{ is_object($activity) && method_exists($activity, 'created_at') ? $activity->created_at->format('M d, Y H:i') : '' }}
                                    </td>
                                    <td>{{ is_object($activity) && isset($activity->causer) ? $activity->causer->name ?? 'System' : 'System' }}
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = 'bg-secondary';
                                            $logName = 'System Action';
                                            if (
                                                is_object($activity) &&
                                                property_exists($activity, 'log_name') &&
                                                $activity->log_name
                                            ) {
                                                $logName = ucwords(str_replace('_', ' ', $activity->log_name));
                                                if (str_contains($activity->log_name, 'user')) {
                                                    $badgeClass = 'bg-primary';
                                                } elseif (
                                                    str_contains($activity->log_name, 'verification') ||
                                                    str_contains($activity->log_name, 'verified')
                                                ) {
                                                    $badgeClass = 'bg-success';
                                                } elseif (
                                                    str_contains($activity->log_name, 'reject') ||
                                                    str_contains($activity->log_name, 'delete')
                                                ) {
                                                    $badgeClass = 'bg-danger';
                                                } elseif (str_contains($activity->log_name, 'login')) {
                                                    $badgeClass = 'bg-info';
                                                }
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $logName }}
                                        </span>
                                    </td>
                                    <td>{{ is_object($activity) ? $activity->description ?? '' : '' }}</td>
                                    <td>
                                        @if (is_object($activity) && isset($activity->subject_type))
                                            @if ($activity->subject_type === 'App\Models\CustomerProfile')
                                                Customer Profile
                                            @elseif($activity->subject_type === 'App\Models\User')
                                                User
                                            @elseif($activity->subject_type)
                                                {{ class_basename($activity->subject_type) }}
                                            @else
                                                -
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if (is_object($activity) && isset($activity->subject) && $activity->subject)
                                            @if (isset($activity->subject_type) && $activity->subject_type === 'App\Models\CustomerProfile')
                                                <a href="{{ route('admin.customer.view', $activity->subject_id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @elseif(isset($activity->subject_type) && $activity->subject_type === 'App\Models\User')
                                                <a href="{{ route('admin.users.edit', $activity->subject_id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-user"></i>
                                                </a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No activity logs found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Showing {{ $activities->flatten()->count() }} logs
                </div>
                <div>
                    {{ $activities->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .timeline {
                position: relative;
                padding-left: 2rem;
            }

            .timeline::before {
                content: "";
                position: absolute;
                top: 0;
                bottom: 0;
                left: 8px;
                width: 2px;
                background-color: var(--fnbb-gray-200);
            }

            .timeline-item {
                position: relative;
                padding-bottom: 1.5rem;
            }

            .timeline-item:last-child {
                padding-bottom: 0;
            }

            .timeline-icon {
                position: absolute;
                left: -2rem;
                top: 0;
                width: 18px;
                height: 18px;
                border-radius: 50%;
                background-color: white;
                border: 2px solid var(--fnbb-primary);
                z-index: 1;
            }

            .timeline-icon.primary {
                border-color: var(--fnbb-primary);
            }

            .timeline-icon.success {
                border-color: var(--fnbb-success);
            }

            .timeline-icon.danger {
                border-color: var(--fnbb-danger);
            }

            .timeline-icon.info {
                border-color: var(--fnbb-info);
            }

            .timeline-icon.secondary {
                border-color: var(--fnbb-gray-500);
            }

            .timeline-content {
                background-color: white;
                border-radius: 0.5rem;
                padding: 1rem;
                box-shadow: var(--shadow-sm);
                margin-bottom: 0.5rem;
            }

            .timeline-time {
                font-size: 0.75rem;
                color: var(--fnbb-gray-500);
            }

            .date-separator {
                position: relative;
                text-align: center;
                margin-top: 2rem;
                margin-bottom: 2rem;
            }

            .date-separator::before {
                content: "";
                position: absolute;
                top: 50%;
                left: 0;
                right: 0;
                border-top: 1px solid var(--fnbb-gray-200);
                z-index: 1;
            }

            .date-label {
                position: relative;
                display: inline-block;
                padding: 0 1rem;
                background-color: white;
                font-weight: 600;
                color: var(--fnbb-gray-600);
                font-size: 0.875rem;
                z-index: 2;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Toggle views
                const timelineView = document.getElementById('timelineView');
                const tableView = document.getElementById('tableView');
                const viewButtons = document.querySelectorAll('[data-view]');

                viewButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        // Remove active class from all buttons
                        viewButtons.forEach(btn => btn.classList.remove('active'));
                        // Add active class to clicked button
                        this.classList.add('active');

                        const view = this.dataset.view;
                        if (view === 'timeline') {
                            timelineView.style.display = 'block';
                            tableView.style.display = 'none';
                        } else {
                            timelineView.style.display = 'none';
                            tableView.style.display = 'block';
                        }
                    });
                });

                // Refresh logs
                document.getElementById('refreshLogs')?.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.location.reload();
                });

                // Change page size
                document.getElementById('pageSize')?.addEventListener('change', function() {
                    const url = new URL(window.location.href);
                    url.searchParams.set('per_page', this.value);
                    window.location.href = url.toString();
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
