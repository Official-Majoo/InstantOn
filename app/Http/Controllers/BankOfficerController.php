<?php

namespace App\Http\Controllers;

use App\Models\CustomerProfile;
use App\Models\BankOfficerReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BankOfficerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Check if the current user is a bank officer
     * 
     * @return bool
     */
    private function ensureBankOfficer()
    {
        $user = Auth::user();

        if (!$user || !$user->isBankOfficer()) {
            abort(403, 'Unauthorized action. Bank officer access required.');
        }

        return true;
    }

    /**
     * Bank officer dashboard
     */
    public function dashboard()
    {
        // Manually check the user's role
        $this->ensureBankOfficer();

        // Get pending verification count
        $pendingCount = CustomerProfile::whereHas('verificationSessions', function ($query) {
            $query->where('status', 'pending');
        })
            ->whereDoesntHave('reviews', function ($query) {
                $query->where('officer_id', Auth::id());
            })
            ->count();

        // Get officer's recent reviews
        $recentReviews = BankOfficerReview::where('officer_id', Auth::id())
            ->with('customerProfile')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Daily verification statistics
        $today = now()->startOfDay();
        $startOfWeek = now()->startOfWeek();
        $dailyStats = [
            'today_reviewed' => BankOfficerReview::where('officer_id', Auth::id())
                ->where('created_at', '>=', $today)
                ->count(),
            'today_approved' => BankOfficerReview::where('officer_id', Auth::id())
                ->where('status', 'approved')
                ->where('created_at', '>=', $today)
                ->count(),
            'today_rejected' => BankOfficerReview::where('officer_id', Auth::id())
                ->where('status', 'rejected')
                ->where('created_at', '>=', $today)
                ->count(),
            'week_reviewed' => BankOfficerReview::where('officer_id', Auth::id())
                ->where('created_at', '>=', $startOfWeek)
                ->count(),
        ];

        return view('officer.dashboard', compact('pendingCount', 'recentReviews', 'dailyStats'));
    }

    /**
     * Show review queue
     * 
     * This method displays the review queue page which contains the OfficerReviewQueue
     * Livewire component. The actual data loading and interaction is handled by the
     * Livewire component itself.
     */
    public function reviewQueue()
    {
        // Manually check the user's role
        $this->ensureBankOfficer();

        // Get some basic statistics for the view
        $stats = [
            'pending_count' => CustomerProfile::whereHas('verificationSessions', function ($query) {
                $query->where('status', 'pending');
            })
                ->whereDoesntHave('reviews', function ($query) {
                    $query->where('officer_id', Auth::id());
                })
                ->count(),
            'total_reviews' => BankOfficerReview::where('officer_id', Auth::id())->count(),
        ];

        return view('officer.queue', compact('stats'));
    }

    /**
     * Show customer details
     */
    public function customerDetails($id)
    {
        // Manually check the user's role
        $this->ensureBankOfficer();

        $customerProfile = CustomerProfile::with(['user', 'documents', 'verificationSessions', 'reviews'])
            ->findOrFail($id);

        // Check if this officer has already reviewed
        $reviewed = $customerProfile->reviews()
            ->where('officer_id', Auth::id())
            ->exists();

        return view('officer.customer-details', compact('customerProfile', 'reviewed'));
    }

    /**
     * Submit review
     */
    public function submitReview(Request $request)
    {
        // Manually check the user's role
        $this->ensureBankOfficer();

        $validatedData = $request->validate([
            'customer_profile_id' => 'required|exists:customer_profiles,id',
            'status' => 'required|in:approved,rejected,pending_additional_info',
            'notes' => 'required|string|min:5',
        ]);

        // Use where()->first() pattern to ensure a single model
        $customerProfile = CustomerProfile::where('id', $validatedData['customer_profile_id'])
            ->first();

        if (!$customerProfile) {
            return back()->withErrors([
                'customer_profile_id' => 'Customer profile not found.',
            ]);
        }

        // Check if this officer has already reviewed
        $existingReview = BankOfficerReview::where('customer_profile_id', $customerProfile->id)
            ->where('officer_id', Auth::id())
            ->first();

        if ($existingReview) {
            return back()->withErrors([
                'review' => 'You have already reviewed this customer.',
            ]);
        }

        // Create review
        BankOfficerReview::create([
            'customer_profile_id' => $customerProfile->id,
            'officer_id' => Auth::id(),
            'status' => $validatedData['status'],
            'notes' => $validatedData['notes'],
            'review_timestamp' => now(),
        ]);

        // Update verification sessions
        $customerProfile->verificationSessions()
            ->where('status', 'pending')
            ->update([
                'status' => $validatedData['status'] === 'approved' ? 'approved' : 'rejected',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'notes' => $validatedData['notes'],
            ]);

        // Update customer profile status
        if ($validatedData['status'] === 'approved') {
            $customerProfile->update([
                'verification_status' => 'verified',
            ]);

            $customerProfile->user->update([
                'status' => 'active',
            ]);
        } elseif ($validatedData['status'] === 'rejected') {
            $customerProfile->update([
                'verification_status' => 'rejected',
                'rejection_reason' => $validatedData['notes'],
            ]);

            $customerProfile->user->update([
                'status' => 'rejected',
            ]);
        }

        // Log activity
        activity()
            ->performedOn($customerProfile)
            ->causedBy(Auth::user())
            ->withProperties([
                'status' => $validatedData['status'],
                'notes' => $validatedData['notes'],
            ])
            ->log('customer_review_submitted');

        return redirect()->route('officer.queue')
            ->with('success', 'Review submitted successfully.');
    }

    /**
     * Show reports page
     */
    public function reports()
    {
        // Manually check the user's role
        $this->ensureBankOfficer();

        // Get registration statistics
        $stats = [
            'total_registrations' => CustomerProfile::count(),
            'pending_registrations' => CustomerProfile::where('verification_status', 'pending')->count(),
            'verified_registrations' => CustomerProfile::where('verification_status', 'verified')->count(),
            'rejected_registrations' => CustomerProfile::where('verification_status', 'rejected')->count(),
        ];

        // Get officer statistics
        $officerStats = BankOfficerReview::select('officer_id')
            ->selectRaw('COUNT(*) as total_reviews')
            ->selectRaw('SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved_count')
            ->selectRaw('SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected_count')
            ->selectRaw('SUM(CASE WHEN status = "pending_additional_info" THEN 1 ELSE 0 END) as pending_info_count')
            ->groupBy('officer_id')
            ->with('officer')
            ->get();

        // Get monthly registration trends
        $sixMonthsAgo = now()->subMonths(6)->startOfMonth();
        $monthlyTrends = CustomerProfile::where('created_at', '>=', $sixMonthsAgo)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        return view('officer.reports', compact('stats', 'officerStats', 'monthlyTrends'));
    }
}