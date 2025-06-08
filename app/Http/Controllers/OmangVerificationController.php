<?php

namespace App\Http\Controllers;

use App\Http\Requests\OmangVerificationRequest;
use App\Models\CustomerProfile;
use App\Services\OmangApiService;
use App\Services\RegistrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OmangVerificationController extends Controller
{
    protected $omangApiService;
    protected $registrationService;

    public function __construct(
        OmangApiService $omangApiService,
        RegistrationService $registrationService
    ) {
        $this->omangApiService = $omangApiService;
        $this->registrationService = $registrationService;

        $this->middleware('auth');
    }

    /**
     * Show the Omang verification form
     */
    public function showVerificationForm()
    {
        $customerProfile = Auth::user()->customerProfile;

        if (!$customerProfile) {
            return view('verification.omang', ['needsProfile' => true]);
        }

        return view('verification.omang', compact('customerProfile'));
    }

    /**
     * Verify Omang number against API
     */
    public function verify(OmangVerificationRequest $request)
    {
        $user = Auth::user();
        $customerProfile = $user->customerProfile;

        // If no profile exists, create one
        if (!$customerProfile) {
            try {
                // Create a new customer profile with minimum valid values
                $customerProfile = CustomerProfile::create([
                    'user_id' => $user->id,
                    'omang_number' => $request->omang_number,
                    'verification_status' => 'pending',
                    'first_name' => 'Pending',
                    'last_name' => 'Verification',
                    'gender' => 'other',  // Using 'other' as a safe default from your enum values
                    'date_of_birth' => now()->subYears(18),
                    'nationality' => 'Botswana',

                    // Optional fields can be null or have default values
                    'middle_name' => null,
                    'address' => null,
                    'postal_code' => null,
                    'city' => null,
                    'district' => null,
                    'occupation' => null,
                    'employer' => null,
                    'income_range' => null,
                ]);

                // Log the profile creation
                activity()
                    ->performedOn($customerProfile)
                    ->causedBy($user)
                    ->withProperties(['omang_number' => $request->omang_number])
                    ->log('customer_profile_created');

            } catch (\Exception $e) {
                // Log the error
                \Log::error('Error creating customer profile', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                // Return with error
                return back()->withErrors([
                    'profile_creation' => 'Could not create customer profile. Error: ' . $e->getMessage()
                ])->withInput();
            }
        }
        // Update Omang number if profile exists and number is provided
        else if ($request->filled('omang_number')) {
            $customerProfile->update([
                'omang_number' => $request->omang_number,
            ]);
        }

        // Verify against Omang API
        $result = $this->registrationService->verifyOmang($customerProfile);

        if (!$result['success']) {
            return back()->withErrors([
                'omang_number' => $result['message'],
            ])->withInput();
        }

        // Redirect to document upload step
        return redirect()->route('verification.documents')
            ->with('success', 'Omang verification successful.');
    }

    /**
     * Check verification status (AJAX)
     */
    public function checkStatus(Request $request)
    {
        $customerProfile = Auth::user()->customerProfile;

        if (!$customerProfile) {
            return response()->json([
                'success' => false,
                'message' => 'Customer profile not found',
            ]);
        }

        return response()->json([
            'success' => true,
            'verification_status' => $customerProfile->verification_status,
            'is_verified' => $customerProfile->isVerified(),
        ]);
    }
}