<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Show the appropriate dashboard based on user role
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isBankOfficer()) {
            return $this->bankOfficerDashboard();
        } else {
            return $this->customerDashboard();
        }
    }
    
    /**
     * Admin dashboard
     */
    protected function adminDashboard()
    {
        // Get statistics for admin dashboard
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_customers' => \App\Models\User::where('role', 'customer')->count(),
            'pending_registrations' => \App\Models\CustomerProfile::where('verification_status', 'pending')->count(),
            'verified_customers' => \App\Models\CustomerProfile::where('verification_status', 'verified')->count(),
            'rejected_customers' => \App\Models\CustomerProfile::where('verification_status', 'rejected')->count(),
            'bank_officers' => \App\Models\User::where('role', 'bank_officer')->count(),
        ];
        
        // Get recent registrations
        $recentRegistrations = \App\Models\CustomerProfile::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        return view('dashboard.admin', compact('stats', 'recentRegistrations'));
    }
    
    /**
     * Bank officer dashboard
     */
    protected function bankOfficerDashboard()
    {
        // Get pending verification count
        $pendingCount = \App\Models\CustomerProfile::whereHas('verificationSessions', function ($query) {
                $query->where('status', 'pending');
            })
            ->whereDoesntHave('reviews', function ($query) {
                $query->where('officer_id', Auth::id());
            })
            ->count();
        
        // Get officer's recent reviews
        $recentReviews = \App\Models\BankOfficerReview::where('officer_id', Auth::id())
            ->with('customerProfile')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Daily verification statistics
        $today = now()->startOfDay();
        $startOfWeek = now()->startOfWeek();
        $dailyStats = [
            'today_reviewed' => \App\Models\BankOfficerReview::where('officer_id', Auth::id())
                ->where('created_at', '>=', $today)
                ->count(),
            'today_approved' => \App\Models\BankOfficerReview::where('officer_id', Auth::id())
                ->where('status', 'approved')
                ->where('created_at', '>=', $today)
                ->count(),
            'today_rejected' => \App\Models\BankOfficerReview::where('officer_id', Auth::id())
                ->where('status', 'rejected')
                ->where('created_at', '>=', $today)
                ->count(),
            'week_reviewed' => \App\Models\BankOfficerReview::where('officer_id', Auth::id())
                ->where('created_at', '>=', $startOfWeek)
                ->count(),
        ];
        
        return view('dashboard.officer', compact('pendingCount', 'recentReviews', 'dailyStats'));
    }
    
    /**
     * Customer dashboard
     */
    protected function customerDashboard()
    {
        $user = Auth::user();
        $customerProfile = $user->customerProfile;
        
        if (!$customerProfile) {
            return redirect()->route('registration.start');
        }
        
        // Check registration status and display appropriate content
        $status = [
            'is_verified' => $customerProfile->verification_status === 'verified',
            'is_rejected' => $customerProfile->verification_status === 'rejected',
            'is_pending' => $customerProfile->verification_status === 'pending',
            'rejection_reason' => $customerProfile->rejection_reason,
        ];
        
        // Get documents
        $documents = $customerProfile->documents()->get();
        
        // Get verification sessions
        $verificationSessions = $customerProfile->verificationSessions()
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('dashboard.customer', compact('customerProfile', 'status', 'documents', 'verificationSessions'));
    }
}