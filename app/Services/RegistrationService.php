<?php

namespace App\Services;

use App\Models\User;
use App\Models\CustomerProfile;
use App\Models\VerificationDocument;
use App\Events\CustomerRegistered;
use App\Events\OmangVerified;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class RegistrationService
{
    protected $omangApiService;

    public function __construct(OmangApiService $omangApiService)
    {
        $this->omangApiService = $omangApiService;
    }

    /**
     * Register a new customer
     * 
     * @param array $userData
     * @return array
     */
    public function registerCustomer(array $userData)
    {
        try {
            DB::beginTransaction();

            // Create the user
            $user = User::create([
                'name' => $userData['first_name'] . ' ' . $userData['last_name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'phone_number' => $userData['phone_number'] ?? null,
                'role' => 'customer',
                'status' => 'pending',
            ]);

            // Assign customer role
            $user->assignRole('customer');

            // Create customer profile with basic info
            $customerProfile = CustomerProfile::create([
                'user_id' => $user->id,
                'omang_number' => $userData['omang_number'],
                'first_name' => $userData['first_name'],
                'middle_name' => $userData['middle_name'] ?? null,
                'last_name' => $userData['last_name'],
                'date_of_birth' => $userData['date_of_birth'],
                'gender' => $userData['gender'],
                'verification_status' => 'pending',
            ]);

            DB::commit();

            // Dispatch event
            event(new CustomerRegistered($user, $customerProfile));

            return [
                'success' => true,
                'user' => $user,
                'customer_profile' => $customerProfile,
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Customer registration error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Error during registration: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Verify customer's Omang details
     * 
     * @param CustomerProfile $customerProfile
     * @return array
     */
    public function verifyOmang(CustomerProfile $customerProfile)
    {
        $omangNumber = $customerProfile->omang_number;

        // Call Omang API
        $response = $this->omangApiService->verifyOmang($omangNumber, $customerProfile);

        if (!isset($response['success']) || !$response['success']) {
            return [
                'success' => false,
                'message' => $response['message'] ?? 'Omang verification failed',
            ];
        }

        // Update customer profile with verified details
        $omangData = $response['data'];

        $customerProfile->update([
            'first_name' => $omangData['first_name'],
            'middle_name' => $omangData['middle_name'] ?? null,
            'last_name' => $omangData['last_name'],
            'date_of_birth' => $omangData['date_of_birth'],
            'gender' => $omangData['gender'],
            'nationality' => $omangData['nationality'] ?? 'Botswana',
        ]);

        // Mark Omang as verified
        $customerProfile->update([
            'verification_status' => 'verified',
        ]);

        // Dispatch event
        event(new OmangVerified($customerProfile, $omangData));

        return [
            'success' => true,
            'customer_profile' => $customerProfile,
            'omang_data' => $omangData,
        ];
    }

    /**
     * Process document upload
     * 
     * @param CustomerProfile $customerProfile
     * @param array $documentData
     * @return array
     */
    public function uploadDocument(CustomerProfile $customerProfile, array $documentData)
    {
        try {
            $file = $documentData['file'];
            $documentType = $documentData['document_type'];

            // Get original filename
            $originalFilename = $file->getClientOriginalName();

            // Store file with a unique name in the secure disk
            $path = $file->store('verification_documents/' . $documentType, 'secure');

            $document = VerificationDocument::create([
                'customer_profile_id' => $customerProfile->id,
                'document_type' => $documentType,
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'original_filename' => $originalFilename, // Store the original filename
                'uploaded_at' => now(),
                'verification_status' => 'pending',
            ]);

            return [
                'success' => true,
                'document' => $document,
            ];
        } catch (\Exception $e) {
            \Log::error('Document upload error', [
                'customer_profile_id' => $customerProfile->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Error uploading document: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Complete customer registration process
     * 
     * @param CustomerProfile $customerProfile
     * @param array $additionalData
     * @return array
     */
    public function completeRegistration(CustomerProfile $customerProfile, array $additionalData)
    {
        try {
            // Update customer profile with additional information
            $customerProfile->update([
                'address' => $additionalData['address'],
                'postal_code' => $additionalData['postal_code'],
                'city' => $additionalData['city'],
                'district' => $additionalData['district'],
                'occupation' => $additionalData['occupation'],
                'employer' => $additionalData['employer'] ?? null,
                'income_range' => $additionalData['income_range'],
            ]);

            // Update user status
            $customerProfile->user->update([
                'status' => 'active',
            ]);

            return [
                'success' => true,
                'customer_profile' => $customerProfile,
            ];
        } catch (\Exception $e) {
            Log::error('Registration completion error', [
                'customer_profile_id' => $customerProfile->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Error completing registration: ' . $e->getMessage(),
            ];
        }
    }
}