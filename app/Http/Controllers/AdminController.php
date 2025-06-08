<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CustomerProfile;
use App\Models\BankOfficerReview;
use App\Models\VerificationDocument;
use App\Models\VerificationSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\Models\Activity;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('ensure.admin');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Get statistics for admin dashboard
        $stats = [
            'total_users' => User::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'pending_registrations' => CustomerProfile::where('verification_status', 'pending')->count(),
            'verified_customers' => CustomerProfile::where('verification_status', 'verified')->count(),
            'rejected_customers' => CustomerProfile::where('verification_status', 'rejected')->count(),
            'bank_officers' => User::where('role', 'bank_officer')->count(),
        ];
        
        // Get recent registrations
        $recentRegistrations = CustomerProfile::with('user', 'documents', 'verificationSessions')
            ->orderBy('created_at', 'desc')
            ->take(10)
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
            
        // Get recent activity logs
        $recentActivities = Activity::with('causer', 'subject')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Get new logs count (unread logs in the last 24 hours)
        $newLogsCount = Activity::where('created_at', '>=', now()->subDay())->count();
        
        return view('dashboard.admin', compact('stats', 'recentRegistrations', 'monthlyTrends', 'recentActivities', 'newLogsCount'));
    }
    
    /**
     * Show all users
     *
     * @return \Illuminate\View\View
     */
    public function users()
    {
        $users = User::with('roles')->paginate(15);
        return view('admin.users.index', compact('users'));
    }
    
    /**
     * Show create user form
     *
     * @return \Illuminate\View\View
     */
    public function createUser()
    {
        return view('admin.users.create');
    }
    
    /**
     * Store a newly created user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,bank_officer,customer',
            'phone_number' => 'nullable|string|max:15',
        ]);
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone_number' => $validated['phone_number'],
            'role' => $validated['role'],
            'status' => 'active',
        ]);
        
        // Assign role
        $user->assignRole($validated['role']);
        
        // Log activity
        activity()
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->log('created user');
            
        return redirect()->route('admin.users')
            ->with('success', 'User created successfully');
    }
    
    /**
     * Show edit user form
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }
    
    /**
     * Update the specified user
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,bank_officer,customer',
            'phone_number' => 'nullable|string|max:15',
            'status' => 'required|in:active,inactive,pending,rejected',
        ]);
        
        // Update user
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);
        
        // Sync roles if role has changed
        if ($user->roles->first()->name !== $validated['role']) {
            $user->syncRoles([$validated['role']]);
        }
        
        // Log activity
        activity()
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->log('updated user');
            
        return redirect()->route('admin.users')
            ->with('success', 'User updated successfully');
    }
    
    /**
     * Delete a user
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Check if user is the logged in user
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users')
                ->with('error', 'You cannot delete your own account');
        }
        
        // Log activity before deletion
        activity()
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->log('deleted user');
            
        // Delete user
        $user->delete();
        
        return redirect()->route('admin.users')
            ->with('success', 'User deleted successfully');
    }
    
    /**
     * Show all bank officers
     *
     * @return \Illuminate\View\View
     */
    public function officers()
    {
        $officers = User::where('role', 'bank_officer')
            ->withCount(['reviews', 
                'reviews as approved_count' => function ($query) {
                    $query->where('status', 'approved');
                },
                'reviews as rejected_count' => function ($query) {
                    $query->where('status', 'rejected');
                }
            ])
            ->paginate(15);
            
        return view('admin.officers.index', compact('officers'));
    }
    
    /**
     * Show all registrations
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function registrations(Request $request)
    {
        $query = CustomerProfile::with('user', 'documents', 'verificationSessions');
        
        // Filter by status if provided
        if ($request->has('status') && in_array($request->status, ['verified', 'pending', 'rejected'])) {
            $query->where('verification_status', $request->status);
        }
        
        // Filter by search term if provided
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('omang_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('email', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%");
                  });
            });
        }
        
        // Sort results
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        if (in_array($sortField, ['created_at', 'first_name', 'omang_number', 'verification_status'])) {
            $query->orderBy($sortField, $sortDirection);
        }
        
        $registrations = $query->paginate(15)->withQueryString();
        
        return view('admin.registrations.index', compact('registrations'));
    }
    
    /**
     * Show customer details
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function viewCustomer($id)
    {
        $customerProfile = CustomerProfile::with([
                'user', 
                'documents', 
                'verificationSessions',
                'reviews.officer',
                'omangVerificationLogs'
            ])
            ->findOrFail($id);
            
        // Get activities related to this customer
        $activities = Activity::where(function($query) use ($customerProfile) {
                $query->where('subject_type', CustomerProfile::class)
                      ->where('subject_id', $customerProfile->id);
            })
            ->orWhere(function($query) use ($customerProfile) {
                $query->where('subject_type', User::class)
                      ->where('subject_id', $customerProfile->user_id);
            })
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
            
        return view('admin.registrations.view', compact('customerProfile', 'activities'));
    }
    
    /**
     * Update customer verification status
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCustomerStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'verification_status' => 'required|in:verified,pending,rejected',
            'rejection_reason' => 'required_if:verification_status,rejected|nullable|string',
        ]);
        
        $customerProfile = CustomerProfile::findOrFail($id);
        
        // Update customer profile
        $customerProfile->update([
            'verification_status' => $validated['verification_status'],
            'rejection_reason' => $validated['verification_status'] === 'rejected' ? $validated['rejection_reason'] : null,
        ]);
        
        // Update user status
        $customerProfile->user->update([
            'status' => $validated['verification_status'] === 'verified' ? 'active' : 
                       ($validated['verification_status'] === 'rejected' ? 'rejected' : 'pending'),
        ]);
        
        // Create an admin review record
        BankOfficerReview::create([
            'customer_profile_id' => $customerProfile->id,
            'officer_id' => Auth::id(),
            'status' => $validated['verification_status'],
            'notes' => $validated['rejection_reason'] ?? 'Status updated by admin',
            'review_timestamp' => now(),
        ]);
        
        // Log activity
        activity()
            ->causedBy(Auth::user())
            ->performedOn($customerProfile)
            ->withProperties([
                'status' => $validated['verification_status'],
                'notes' => $validated['rejection_reason'] ?? 'Status updated by admin',
            ])
            ->log('updated verification status');
            
        return redirect()->route('admin.customer.view', $customerProfile->id)
            ->with('success', 'Customer verification status updated successfully');
    }
    
    /**
     * Show activity logs
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function activityLogs(Request $request)
    {
        $query = Activity::with('causer', 'subject');
        
        // Filter by log name if provided
        if ($request->has('type') && !empty($request->type)) {
            $query->where('log_name', $request->type);
        }
        
        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00', 
                $request->end_date . ' 23:59:59'
            ]);
        }
        
        // Filter by causer if provided
        if ($request->has('causer_id') && !empty($request->causer_id)) {
            $query->where('causer_id', $request->causer_id);
        }
        
        $activities = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();
            
        // Get log types for filter dropdown
        $logTypes = Activity::select('log_name')
            ->distinct()
            ->pluck('log_name');
            
        // Get users for filter dropdown
        $users = User::orderBy('name')->get();
        
        return view('admin.logs.index', compact('activities', 'logTypes', 'users'));
    }
    
    /**
     * Show system settings form
     *
     * @return \Illuminate\View\View
     */
    public function settings()
    {
        return view('admin.settings.index');
    }
    
    /**
     * Show security settings form
     *
     * @return \Illuminate\View\View
     */
    public function security()
    {
        return view('admin.settings.security');
    }
    
    /**
     * Show API configuration form
     *
     * @return \Illuminate\View\View
     */
    public function apiConfig()
    {
        return view('admin.settings.api');
    }
    
    /**
     * Show roles and permissions
     *
     * @return \Illuminate\View\View
     */
    public function roles()
    {
        return view('admin.roles.index');
    }
    
    /**
     * Show reports dashboard
     *
     * @return \Illuminate\View\View
     */
    public function reports()
    {
        return view('admin.reports.index');
    }
    
    /**
     * Generate system report
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generateReport(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:registrations,verifications,officer_performance,system_activity',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:pdf,excel,csv',
        ]);
        
        // Logic to generate report based on type and date range
        // This would typically call a report service or export job
        
        return redirect()->back()
            ->with('success', 'Report generation started. You will be notified when it is ready for download.');
    }
}