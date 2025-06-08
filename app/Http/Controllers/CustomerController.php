<?php

// app/Http/Controllers/CustomerController.php
namespace App\Http\Controllers;

use App\Models\CustomerProfile;
use App\Services\RegistrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    protected $registrationService;
    
    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
        
        $this->middleware('auth')->except(['showRegistrationForm']);
    }
    
    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        // If user is already logged in and has a profile, redirect to appropriate page
        if (Auth::check() && Auth::user()->customerProfile) {
            $profile = Auth::user()->customerProfile;
            
            if ($profile->verification_status === 'pending') {
                return redirect()->route('verification.omang');
            } elseif ($profile->verification_status === 'verified') {
                return redirect()->route('dashboard');
            } elseif ($profile->verification_status === 'rejected') {
                return redirect()->route('dashboard')->with('error', 'Your registration has been rejected. Please contact customer support.');
            }
        }
        
        return view('registration.start');
    }
    
    /**
     * Show the document upload page
     */
    public function showDocumentUpload()
    {
        $customerProfile = Auth::user()->customerProfile;
        
        if (!$customerProfile) {
            return redirect()->route('registration.start')
                ->with('error', 'Please complete your registration first.');
        }
        
        if ($customerProfile->verification_status !== 'verified') {
            return redirect()->route('verification.omang')
                ->with('error', 'Please complete Omang verification first.');
        }
        
        return view('verification.documents', compact('customerProfile'));
    }
    
    /**
     * Show the additional information form
     */
    public function showAdditionalInfoForm()
    {
        $customerProfile = Auth::user()->customerProfile;
        
        if (!$customerProfile) {
            return redirect()->route('registration.start')
                ->with('error', 'Please complete your registration first.');
        }
        
        // Check if document upload is complete
        $requiredDocumentTypes = ['omang_front', 'omang_back', 'proof_of_address'];
        $documents = $customerProfile->documents()->whereIn('document_type', $requiredDocumentTypes)->get();
        $documentTypes = $documents->pluck('document_type')->toArray();
        
        foreach ($requiredDocumentTypes as $type) {
            if (!in_array($type, $documentTypes)) {
                return redirect()->route('verification.documents')
                    ->with('error', 'Please upload all required documents first.');
            }
        }
        
        // Check if facial verification is complete
        $latestSession = $customerProfile->verificationSessions()->latest()->first();
        if (!$latestSession || $latestSession->status !== 'approved') {
            return redirect()->route('verification.facial')
                ->with('error', 'Please complete facial verification first.');
        }
        
        return view('verification.additional', compact('customerProfile'));
    }
    
    /**
     * Submit additional information
     */
    public function submitAdditionalInfo(Request $request)
    {
        $customerProfile = Auth::user()->customerProfile;
        
        if (!$customerProfile) {
            return redirect()->route('registration.start')
                ->with('error', 'Please complete your registration first.');
        }
        
        $validatedData = $request->validate([
            'address' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'occupation' => 'required|string|max:100',
            'employer' => 'nullable|string|max:100',
            'income_range' => 'required|in:below_5000,5000_to_10000,10001_to_25000,25001_to_50000,above_50000',
        ]);
        
        $result = $this->registrationService->completeRegistration($customerProfile, $validatedData);
        
        if (!$result['success']) {
            return back()->withErrors([
                'registration' => $result['message'],
            ])->withInput();
        }
        
        return redirect()->route('registration.success');
    }
    
    /**
     * Show registration success page
     */
    public function registrationSuccess()
    {
        $customerProfile = Auth::user()->customerProfile;
        
        if (!$customerProfile) {
            return redirect()->route('registration.start');
        }
        
        return view('registration.success', compact('customerProfile'));
    }
    
    /**
     * Show customer profile page
     */
    public function showProfile()
    {
        $customerProfile = Auth::user()->customerProfile;
        
        if (!$customerProfile) {
            return redirect()->route('registration.start');
        }
        
        return view('customer.profile', compact('customerProfile'));
    }
    
    /**
     * Update customer profile
     */
    public function updateProfile(Request $request)
    {
        $customerProfile = Auth::user()->customerProfile;
        
        if (!$customerProfile) {
            return redirect()->route('registration.start');
        }
        
        $validatedData = $request->validate([
            'phone_number' => 'required|string|min:8|max:15',
            'address' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'occupation' => 'required|string|max:100',
            'employer' => 'nullable|string|max:100',
            'income_range' => 'required|in:below_5000,5000_to_10000,10001_to_25000,25001_to_50000,above_50000',
        ]);
        
        // Update user
        Auth::user()->update([
            'phone_number' => $validatedData['phone_number'],
        ]);
        
        // Update profile
        $customerProfile->update([
            'address' => $validatedData['address'],
            'postal_code' => $validatedData['postal_code'],
            'city' => $validatedData['city'],
            'district' => $validatedData['district'],
            'occupation' => $validatedData['occupation'],
            'employer' => $validatedData['employer'],
            'income_range' => $validatedData['income_range'],
        ]);
        
        return back()->with('success', 'Profile updated successfully.');
    }
}