@extends('layouts.app')

@section('title', 'Bank Officer Dashboard')

@section('content')
<div class="container py-5">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Bank Officer Dashboard</h1>
        <a href="{{ route('officer.queue') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-list fa-sm text-white-50 me-1"></i> View Review Queue
        </a>
    </div>
    
    <!-- Officer Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Pending Reviews</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Reviewed Today</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dailyStats['today_reviewed'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Approved Today
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dailyStats['today_approved'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-thumbs-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Weekly Reviews</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dailyStats['week_reviewed'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Pending Reviews -->
        <div class="col-lg-7 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Pending Reviews</h6>
                    <a href="{{ route('officer.queue') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    @if($pendingCount > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Omang</th>
                                        <th>Name</th>
                                        <th>Submission Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentReviews as $review)
                                        <tr>
                                            <td>{{ $review->customerProfile->omang_number }}</td>
                                            <td>{{ $review->customerProfile->first_name }} {{ $review->customerProfile->last_name }}</td>
                                            <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('officer.customer.details', $review->customerProfile->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <img src="{{ asset('images/empty-concept-illustration.png') }}" alt="Empty Queue" style="width: 200px;" class="mb-3">
                            <h6 class="mb-1">No Pending Reviews</h6>
                            <p class="text-muted">All customer verifications have been reviewed.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Your Recent Activity -->
        <div class="col-lg-5 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Your Recent Activity</h6>
                </div>
                <div class="card-body">
                    @if($recentReviews->count() > 0)
                        <div class="timeline">
                            @foreach($recentReviews->take(5) as $review)
                                <div class="timeline-item">
                                    <div class="timeline-marker 
                                        @if($review->status === 'approved') bg-success
                                        @elseif($review->status === 'rejected') bg-danger
                                        @else bg-warning @endif">
                                    </div>
                                    <div class="timeline-content">
                                        <h3 class="timeline-title h6">
                                            @if($review->status === 'approved')
                                                Approved Customer Registration
                                            @elseif($review->status === 'rejected')
                                                Rejected Customer Registration
                                            @else
                                                Requested Additional Information
                                            @endif
                                        </h3>
                                        <div class="mb-1">
                                            <strong>{{ $review->customerProfile->first_name }} {{ $review->customerProfile->last_name }}</strong> ({{ $review->customerProfile->omang_number }})
                                        </div>
                                        <p class="timeline-text small text-muted mb-0">
                                            {{ Str::limit($review->notes, 100) }}
                                        </p>
                                        <div class="timeline-time text-muted small">
                                            {{ $review->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <img src="{{ asset('images/empty-concept-illustration.png') }}" alt="No Activity" style="width: 200px;" class="mb-3">
                            <h6 class="mb-1">No Recent Activity</h6>
                            <p class="text-muted">You haven't reviewed any customer registrations yet.</p>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-white">
                    <a href="{{ route('officer.reports') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-chart-bar me-1"></i> View Reports
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tips & Guidelines -->
    <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">Review Guidelines</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="guideline-item">
                        <div class="guideline-icon">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <h5>Document Verification</h5>
                        <ul class="small text-muted">
                            <li>Check that the Omang details match the documents</li>
                            <li>Verify document authenticity and clarity</li>
                            <li>Ensure proof of address is valid and recent</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="guideline-item">
                        <div class="guideline-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <h5>Identity Confirmation</h5>
                        <ul class="small text-muted">
                            <li>Compare selfie with Omang photo</li>
                            <li>Check facial verification score (min. 70%)</li>
                            <li>Look for signs of potential identity fraud</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="guideline-item">
                        <div class="guideline-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h5>Review Process</h5>
                        <ul class="small text-muted">
                            <li>Be thorough but efficient with reviews</li>
                            <li>Provide clear reasons for rejections</li>
                            <li>Escalate suspicious applications to management</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 4px solid var(--fnbb-primary);
}
.border-left-success {
    border-left: 4px solid var(--fnbb-success);
}
.border-left-info {
    border-left: 4px solid var(--fnbb-info);
}
.border-left-warning {
    border-left: 4px solid var(--fnbb-warning);
}

.timeline {
    position: relative;
    padding-left: 1.5rem;
}
.timeline-item {
    position: relative;
    padding-bottom: 1.5rem;
}
.timeline-item:last-child {
    padding-bottom: 0;
}
.timeline-marker {
    position: absolute;
    left: -1.5rem;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}
.timeline-item:not(:last-child):before {
    content: '';
    position: absolute;
    left: -1.5rem;
    width: 2px;
    height: 100%;
    background-color: var(--fnbb-medium-gray);
    transform: translateX(5px);
}
.timeline-content {
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--fnbb-medium-gray);
}
.timeline-item:last-child .timeline-content {
    border-bottom: none;
    padding-bottom: 0;
}
.timeline-time {
    margin-top: 0.5rem;
}

.guideline-item {
    text-align: center;
    padding: 1rem;
}
.guideline-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-color: rgba(0, 162, 165, 0.1);
    color: var(--fnbb-primary);
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}
.guideline-item ul {
    text-align: left;
    padding-left: 1.5rem;
}
</style>
@endsection