<div>
    <div class="row mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <div class="d-flex">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search by name, Omang, email..."
                        wire:model.debounce.500ms="search">
                </div>
                <button type="button" class="btn btn-outline-secondary ms-2" wire:click="$set('search', '')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
            <select class="form-select" wire:model="filter">
                <option value="pending">Pending Reviews</option>
                <option value="reviewed">Already Reviewed</option>
                <option value="approved">Approved by Me</option>
                <option value="rejected">Rejected by Me</option>
                <option value="pending_info">Additional Info Requested</option>
                <option value="all">All Customers</option>
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select" wire:model="perPage">
                <option value="10">10 per page</option>
                <option value="25">25 per page</option>
                <option value="50">50 per page</option>
                <option value="100">100 per page</option>
            </select>
        </div>
    </div>

    @if ($message)
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Customer List -->
    @if ($profiles->count() > 0)
        <div class="row">
            @foreach ($profiles as $profile)
                <div class="col-md-6 col-xl-4">
                    <div class="review-card card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                @php
                                    $latestSelfie = $profile->getLatestSelfie();
                                    $selfiePath = $latestSelfie
                                        ? route('secure.selfie', ['filename' => basename($latestSelfie->file_path)])
                                        : asset('images/default-avatar.png');
                                @endphp
                                <img src="{{ $selfiePath }}" alt="Profile" class="rounded-circle me-3" width="50"
                                    height="50" style="object-fit: cover;">
                                <div>
                                    <h5 class="card-title h6 mb-0">{{ $profile->first_name }} {{ $profile->last_name }}
                                    </h5>
                                    <p class="text-muted small mb-0">{{ $profile->omang_number }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="small text-muted">Email:</div>
                                    <div class="small text-truncate">{{ $profile->user->email }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="small text-muted">Phone:</div>
                                    <div class="small">{{ $profile->user->phone_number }}</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="small text-muted">Documents:</div>
                                    <div class="small">
                                        @php
                                            $documentCount = $profile->documents->count();
                                            $documentColor =
                                                $documentCount >= 3
                                                    ? 'success'
                                                    : ($documentCount > 0
                                                        ? 'warning'
                                                        : 'danger');
                                        @endphp
                                        <span class="text-{{ $documentColor }}">
                                            <i class="fas fa-file me-1"></i> {{ $documentCount }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="small text-muted">Verification:</div>
                                    <div class="small">
                                        @php
                                            $latestSession = $profile->verificationSessions->first();
                                            $verificationColor =
                                                $latestSession && $latestSession->status === 'approved'
                                                    ? 'success'
                                                    : ($latestSession
                                                        ? 'warning'
                                                        : 'danger');
                                            $verificationIcon =
                                                $latestSession && $latestSession->status === 'approved'
                                                    ? 'check-circle'
                                                    : ($latestSession
                                                        ? 'exclamation-circle'
                                                        : 'times-circle');
                                        @endphp
                                        <span class="text-{{ $verificationColor }}">
                                            <i class="fas fa-{{ $verificationIcon }} me-1"></i>
                                            {{ $latestSession ? number_format($latestSession->similarity_score, 1) . '%' : 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $profile->created_at->format('d/m/Y') }}
                                    </span>
                                </div>
                                <button type="button" class="btn btn-sm btn-primary"
                                    wire:click="selectProfile({{ $profile->id }})">
                                    <i class="fas fa-eye me-1"></i> Review
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $profiles->links() }}
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <img src="{{ asset('images/empty-concept-illustration.png') }}" alt="Empty Queue" style="width: 250px;"
                    class="mb-3">
                <h3 class="h5">No Records Found</h3>
                <p class="text-muted">
                    @if ($filter === 'pending')
                        There are no pending customer verifications to review.
                    @elseif($filter === 'reviewed')
                        You haven't reviewed any customer verifications yet.
                    @elseif($filter === 'approved')
                        You haven't approved any customer verifications yet.
                    @elseif($filter === 'rejected')
                        You haven't rejected any customer verifications yet.
                    @elseif($filter === 'pending_info')
                        You haven't requested additional information from any customers yet.
                    @else
                        No customer records match your search criteria.
                    @endif
                </p>
                @if (!empty($search))
                    <button class="btn btn-outline-secondary mt-2" wire:click="$set('search', '')">
                        <i class="fas fa-times me-1"></i> Clear Search
                    </button>
                @endif
            </div>
        </div>
    @endif

    <!-- Review Modal (Simplified for Livewire Component) -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">Submit Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reviewStatus" class="form-label">Review Decision</label>
                        <select class="form-select" id="reviewStatus" wire:model="reviewStatus" required>
                            <option value="" selected disabled>Select a decision</option>
                            <option value="approved">Approve Registration</option>
                            <option value="rejected">Reject Registration</option>
                            <option value="pending_additional_info">Request Additional Information</option>
                        </select>
                        @error('reviewStatus')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="reviewNotes" class="form-label">Review Notes</label>
                        <textarea class="form-control" id="reviewNotes" wire:model="reviewNotes" rows="4" required
                            placeholder="Provide detailed notes about your review decision..."></textarea>
                        <div class="form-text">Please provide a detailed explanation for your decision, especially for
                            rejections or additional information requests.</div>
                        @error('reviewNotes')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" wire:click="submitReview">Submit Review</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Handle Livewire events
        document.addEventListener('livewire:initialized', function() {
            // Listen for event with Livewire v3 syntax
            Livewire.on('openReviewModal', function() {
                var modal = new bootstrap.Modal(document.getElementById('reviewModal'));
                modal.show();
            });

            // Listen for reviewSubmitted event
            Livewire.on('reviewSubmitted', function() {
                // Hide modal after successful submission
                var reviewModal = bootstrap.Modal.getInstance(document.getElementById('reviewModal'));
                if (reviewModal) {
                    reviewModal.hide();
                }

                // Show success toast
                showToast('Review submitted successfully!', 'success');
            });

            Livewire.on('closeModal', function() {
                var reviewModal = bootstrap.Modal.getInstance(document.getElementById('reviewModal'));
                if (reviewModal) {
                    reviewModal.hide();
                }
            });
        });

        // Toast notification function
        function showToast(message, type = 'info') {
            // Create toast container if it doesn't exist
            if (!document.getElementById('toast-container')) {
                const toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
                toastContainer.style.zIndex = '1050';
                document.body.appendChild(toastContainer);
            }

            // Create unique ID for this toast
            const toastId = 'toast-' + Date.now();

            // Set color class based on type
            let colorClass = 'bg-info';
            if (type === 'success') colorClass = 'bg-success';
            if (type === 'warning') colorClass = 'bg-warning';
            if (type === 'error') colorClass = 'bg-danger';

            // Create toast HTML
            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = `toast ${colorClass} text-white`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.innerHTML = `
            <div class="toast-header">
                <strong class="me-auto">FNBB Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        `;

            // Add toast to container
            document.getElementById('toast-container').appendChild(toast);

            // Initialize and show toast
            const bsToast = new bootstrap.Toast(toast, {
                autohide: true,
                delay: 3000
            });
            bsToast.show();

            // Remove toast from DOM after hiding
            toast.addEventListener('hidden.bs.toast', function() {
                document.getElementById(toastId).remove();
            });
        }
    </script>
@endpush