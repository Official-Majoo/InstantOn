@extends('layouts.app')

@section('title', 'Customer Details')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('officer.queue') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Queue
        </a>
    </div>
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Customer Details</h1>
        <div>
            @if(!$reviewed)
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal">
                    <i class="fas fa-clipboard-check me-1"></i> Submit Review
                </button>
            @else
                <span class="badge bg-success p-2">
                    <i class="fas fa-check-circle me-1"></i> Reviewed
                </span>
            @endif
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-4 mb-4">
            <!-- Customer Profile Card -->
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Profile</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="d-flex justify-content-center">
                            @php
                                $latestSelfie = $customerProfile->getLatestSelfie();
                                $selfiePath = $latestSelfie ? Storage::url($latestSelfie->file_path) : asset('images/default-avatar.png');
                            @endphp
                            <img class="img-profile rounded-circle" style="width: 120px; height: 120px; object-fit: cover;" 
                                 src="{{ $selfiePath }}" alt="Customer Photo">
                        </div>
                        <div class="mt-3">
                            <h5 class="font-weight-bold">{{ $customerProfile->first_name }} {{ $customerProfile->last_name }}</h5>
                            <p class="text-muted mb-1">{{ $customerProfile->omang_number }}</p>
                            
                            @if($customerProfile->verification_status === 'verified')
                                <span class="badge bg-success text-white">Verified</span>
                            @elseif($customerProfile->verification_status === 'rejected')
                                <span class="badge bg-danger text-white">Rejected</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="customer-detail-row">
                        <div class="customer-detail-label">Email:</div>
                        <div>{{ $customerProfile->user->email }}</div>
                    </div>
                    <div class="customer-detail-row">
                        <div class="customer-detail-label">Phone:</div>
                        <div>{{ $customerProfile->user->phone_number }}</div>
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
                    
                    @if($customerProfile->address)
                        <hr class="my-3">
                        <div class="customer-detail-row">
                            <div class="customer-detail-label">Address:</div>
                            <div>{{ $customerProfile->address }}</div>
                        </div>
                        <div class="customer-detail-row">
                            <div class="customer-detail-label">City/Town:</div>
                            <div>{{ $customerProfile->city }}</div>
                        </div>
                        <div class="customer-detail-row">
                            <div class="customer-detail-label">District:</div>
                            <div>{{ $customerProfile->district }}</div>
                        </div>
                        <div class="customer-detail-row">
                            <div class="customer-detail-label">Postal Code:</div>
                            <div>{{ $customerProfile->postal_code }}</div>
                        </div>
                    @endif
                    
                    @if($customerProfile->occupation)
                        <hr class="my-3">
                        <div class="customer-detail-row">
                            <div class="customer-detail-label">Occupation:</div>
                            <div>{{ $customerProfile->occupation }}</div>
                        </div>
                        @if($customerProfile->employer)
                            <div class="customer-detail-row">
                                <div class="customer-detail-label">Employer:</div>
                                <div>{{ $customerProfile->employer }}</div>
                            </div>
                        @endif
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
                    @endif
                    
                    <hr class="my-3">
                    <div class="customer-detail-row">
                        <div class="customer-detail-label">Registered:</div>
                        <div>{{ $customerProfile->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="customer-detail-row">
                        <div class="customer-detail-label">Last Updated:</div>
                        <div>{{ $customerProfile->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <!-- Verification Documents -->
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Verification Documents</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($customerProfile->documents as $document)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    @if($document->isImage())
                                        <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="document-link">
                                            <img src="{{ Storage::url($document->file_path) }}" class="card-img-top document-image" alt="{{ $document->document_type }}">
                                        </a>
                                    @elseif($document->isPdf())
                                        <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="document-link">
                                            <div class="pdf-thumbnail">
                                                <i class="fas fa-file-pdf fa-4x text-danger"></i>
                                            </div>
                                        </a>
                                    @else
                                        <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="document-link">
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
                                        <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> View Full Size
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    No documents have been uploaded yet.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Facial Verification Results -->
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Facial Verification Results</h6>
                </div>
                <div class="card-body">
                    @if($customerProfile->verificationSessions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Similarity Score</th>
                                        <th>Status</th>
                                        <th>Images</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customerProfile->verificationSessions as $session)
                                        <tr>
                                            <td>{{ $session->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="progress" style="height: 25px;">
                                                    @php
                                                        $colorClass = $session->similarity_score >= 70 ? 'bg-success' : 
                                                                      ($session->similarity_score >= 50 ? 'bg-warning' : 'bg-danger');
                                                    @endphp
                                                    <div class="progress-bar {{ $colorClass }}" role="progressbar" 
                                                         style="width: {{ min($session->similarity_score, 100) }}%;" 
                                                         aria-valuenow="{{ $session->similarity_score }}" aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                        {{ number_format($session->similarity_score, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($session->status === 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($session->status === 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-6">
                                                        @if($session->selfie_photo_path)
                                                            <a href="{{ Storage::url($session->selfie_photo_path) }}" target="_blank">
                                                                <img src="{{ Storage::url($session->selfie_photo_path) }}" class="img-thumbnail" alt="Selfie" style="width: 60px; height: 60px; object-fit: cover;">
                                                            </a>
                                                        @else
                                                            <span class="text-muted">No selfie</span>
                                                        @endif
                                                    </div>
                                                    <div class="col-6">
                                                        @if($session->omang_photo_path)
                                                            <a href="{{ Storage::url($session->omang_photo_path) }}" target="_blank">
                                                                <img src="{{ Storage::url($session->omang_photo_path) }}" class="img-thumbnail" alt="Omang Photo" style="width: 60px; height: 60px; object-fit: cover;">
                                                            </a>
                                                        @else
                                                            <span class="text-muted">No Omang photo</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            No facial verification sessions have been completed yet.
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Previous Reviews -->
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Review History</h6>
                </div>
                <div class="card-body">
                    @if($customerProfile->reviews->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Officer</th>
                                        <th>Date</th>
                                        <th>Decision</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customerProfile->reviews as $review)
                                        <tr>
                                            <td>{{ $review->officer->name }}</td>
                                            <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @if($review->status === 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($review->status === 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Additional Info</span>
                                                @endif
                                            </td>
                                            <td>{{ $review->notes }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No reviews have been submitted for this customer yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Review Modal -->
@if(!$reviewed)
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">Submit Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('officer.review.submit') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="customer_profile_id" value="{{ $customerProfile->id }}">
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Review Decision</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="" selected disabled>Select a decision</option>
                            <option value="approved">Approve Registration</option>
                            <option value="rejected">Reject Registration</option>
                            <option value="pending_additional_info">Request Additional Information</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Review Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="5" required 
                                  placeholder="Provide detailed notes about your decision..."></textarea>
                        <div class="form-text">
                            Please provide specific details, especially for rejections or requests for additional information.
                        </div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Once submitted, this review cannot be changed. Please ensure all information is accurate.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
.document-image {
    height: 200px;
    object-fit: cover;
}

.pdf-thumbnail, .file-thumbnail {
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
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