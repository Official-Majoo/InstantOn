<?php

namespace App\Http\Livewire;

use App\Models\CustomerProfile;
use App\Models\BankOfficerReview;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OfficerReviewQueue extends Component
{
    use WithPagination;

    public $filter = 'pending';
    public $search = '';
    public $perPage = 10;

    public $selectedProfileId = null;
    public $reviewNotes = '';
    public $reviewStatus = '';
    public $message = null; // Initialize with null

    protected $queryString = [
        'filter' => ['except' => 'pending'],
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    protected $rules = [
        'reviewStatus' => 'required|in:approved,rejected,pending_additional_info',
        'reviewNotes' => 'required|string|min:5',
    ];

    public function mount()
    {
        // Initialize any properties here if needed
        $this->message = null; // Explicitly initialize message
    }

    public function render()
    {
        $query = CustomerProfile::query()
            ->with(['user', 'documents', 'verificationSessions', 'reviews']);

        // Filter by review status
        if ($this->filter === 'pending') {
            // Show profiles that have NOT been reviewed by the current officer
            // and have either a pending verification status or pending verification sessions
            $query->where(function($query) {
                $query->where('verification_status', 'pending')
                    ->orWhereHas('verificationSessions', function($q) {
                        $q->where('status', 'pending');
                    });
            })
            ->whereDoesntHave('reviews', function ($query) {
                $query->where('officer_id', Auth::id());
            });
        } elseif ($this->filter === 'reviewed') {
            // Show all profiles reviewed by the current officer
            $query->whereHas('reviews', function ($query) {
                $query->where('officer_id', Auth::id());
            });
        } elseif ($this->filter === 'approved') {
            // Show profiles approved by the current officer
            $query->whereHas('reviews', function ($query) {
                $query->where('officer_id', Auth::id())
                    ->where('status', 'approved');
            });
        } elseif ($this->filter === 'rejected') {
            // Show profiles rejected by the current officer
            $query->whereHas('reviews', function ($query) {
                $query->where('officer_id', Auth::id())
                    ->where('status', 'rejected');
            });
        } elseif ($this->filter === 'pending_info') {
            // Show profiles where we requested additional information
            $query->whereHas('reviews', function ($query) {
                $query->where('officer_id', Auth::id())
                    ->where('status', 'pending_additional_info');
            });
        } elseif ($this->filter === 'all') {
            // No additional filters - show all profiles
        }

        if ($this->search) {
            $query->where(function ($query) {
                $query->where('omang_number', 'like', "%{$this->search}%")
                    ->orWhere('first_name', 'like', "%{$this->search}%")
                    ->orWhere('last_name', 'like', "%{$this->search}%")
                    ->orWhereHas('user', function ($query) {
                        $query->where('email', 'like', "%{$this->search}%")
                            ->orWhere('phone_number', 'like', "%{$this->search}%");
                    });
            });
        }

        $profiles = $query->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.officer-review-queue', [
            'profiles' => $profiles,
        ]);
    }

    public function selectProfile($profileId)
    {
        $this->selectedProfileId = $profileId;
        $this->resetValidation();
        $this->reset(['reviewNotes', 'reviewStatus', 'message']);

        // Dispatch an event to open the modal
        $this->dispatch('openReviewModal');
    }

    public function submitReview()
    {
        $this->validate();

        try {
            // Use where()->first() pattern to ensure a single model instance
            // This approach is specified in the project architecture for consistent model retrieval
            $customerProfile = CustomerProfile::where('id', $this->selectedProfileId)
                ->first();

            if (!$customerProfile) {
                $this->message = 'Customer profile not found.';
                return;
            }

            // Check if this officer has already reviewed
            $existingReview = BankOfficerReview::where('customer_profile_id', $customerProfile->id)
                ->where('officer_id', Auth::id())
                ->first();

            if ($existingReview) {
                $this->message = 'You have already reviewed this customer.';
                return;
            }

            // Create review
            BankOfficerReview::create([
                'customer_profile_id' => $customerProfile->id,
                'officer_id' => Auth::id(),
                'status' => $this->reviewStatus,
                'notes' => $this->reviewNotes,
                'review_timestamp' => now(),
            ]);

            // Update verification sessions
            $customerProfile->verificationSessions()
                ->where('status', 'pending')
                ->update([
                    'status' => $this->reviewStatus === 'approved' ? 'approved' : 'rejected',
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now(),
                    'notes' => $this->reviewNotes,
                ]);

            // Update customer profile status
            if ($this->reviewStatus === 'approved') {
                $customerProfile->update([
                    'verification_status' => 'verified',
                ]);

                $customerProfile->user->update([
                    'status' => 'active',
                ]);
            } elseif ($this->reviewStatus === 'rejected') {
                $customerProfile->update([
                    'verification_status' => 'rejected',
                    'rejection_reason' => $this->reviewNotes,
                ]);

                $customerProfile->user->update([
                    'status' => 'rejected',
                ]);
            }

            // Log activity with guaranteed single model instance
            activity()
                ->performedOn($customerProfile)
                ->causedBy(Auth::user())
                ->withProperties([
                    'status' => $this->reviewStatus,
                    'notes' => $this->reviewNotes,
                ])
                ->log('customer_review_submitted');

            $this->reset(['selectedProfileId', 'reviewNotes', 'reviewStatus']);
            $this->dispatch('reviewSubmitted');

            session()->flash('message', 'Review submitted successfully.');
        } catch (\Exception $e) {
            Log::error('Error submitting review: ' . $e->getMessage(), [
                'profile_id' => $this->selectedProfileId,
                'trace' => $e->getTraceAsString(),
            ]);
            $this->message = 'Error: ' . $e->getMessage();
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilter()
    {
        $this->resetPage();
    }
}