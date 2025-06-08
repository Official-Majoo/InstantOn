<?php

namespace App\Http\Livewire;

use App\Services\RegistrationService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegistrationWizard extends Component
{
    public $step = 1;
    public $totalSteps = 5;
    public $message = null; // Add this property to fix the unassigned variable error
    
    // Step 1: Basic Information
    public $email;
    public $password;
    public $password_confirmation;
    public $phone_number;
    
    // Step 2: Personal Information
    public $first_name;
    public $middle_name;
    public $last_name;
    public $date_of_birth;
    public $gender;
    public $omang_number;
    
    // Step 3: Omang Verification (handled by OmangVerificationController)
    
    // Step 4: Facial Verification (handled by FacialVerificationController)
    
    // Step 5: Additional Information
    public $address;
    public $postal_code;
    public $city;
    public $district;
    public $occupation;
    public $employer;
    public $income_range;
    
    protected $registrationService;
    
    protected function rules()
    {
        return [
            // Step 1 rules
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()],
            'phone_number' => ['required', 'string', 'min:8', 'max:15'],
            
            // Step 2 rules
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'date_of_birth' => ['required', 'date', 'before:-18 years'],
            'gender' => ['required', 'in:male,female,other'],
            'omang_number' => ['required', 'string', 'size:9', 'regex:/^\d{9}$/', 'unique:customer_profiles,omang_number'],
            
            // Step 5 rules
            'address' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'occupation' => ['required', 'string', 'max:100'],
            'employer' => ['nullable', 'string', 'max:100'],
            'income_range' => ['required', 'in:below_5000,5000_to_10000,10001_to_25000,25001_to_50000,above_50000'],
        ];
    }
    
    public function mount(RegistrationService $registrationService = null)
    {
        $this->registrationService = $registrationService;
    }
    
    public function render()
    {
        return view('livewire.registration-wizard');
    }
    
    public function nextStep()
    {
        try {
            if ($this->step === 1) {
                $this->validate([
                    'email' => ['required', 'email', 'unique:users,email'],
                    'password' => ['required', 'confirmed', Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols()],
                    'phone_number' => ['required', 'string', 'min:8', 'max:15'],
                ]);
            } elseif ($this->step === 2) {
                $this->validate([
                    'first_name' => ['required', 'string', 'max:100'],
                    'middle_name' => ['nullable', 'string', 'max:100'],
                    'last_name' => ['required', 'string', 'max:100'],
                    'date_of_birth' => ['required', 'date', 'before:-18 years'],
                    'gender' => ['required', 'in:male,female,other'],
                    'omang_number' => ['required', 'string', 'size:9', 'regex:/^\d{9}$/', 'unique:customer_profiles,omang_number'],
                ]);
                
                // Register the customer after validating step 2
                if ($this->registrationService) {
                    $this->registerCustomer();
                    return; // The registerCustomer method will handle the redirect
                } else {
                    $this->message = "Registration service not available. Please try again later.";
                    return;
                }
            } elseif ($this->step === 5) {
                $this->validate([
                    'address' => ['required', 'string', 'max:255'],
                    'postal_code' => ['required', 'string', 'max:20'],
                    'city' => ['required', 'string', 'max:100'],
                    'district' => ['required', 'string', 'max:100'],
                    'occupation' => ['required', 'string', 'max:100'],
                    'employer' => ['nullable', 'string', 'max:100'],
                    'income_range' => ['required', 'in:below_5000,5000_to_10000,10001_to_25000,25001_to_50000,above_50000'],
                ]);
                
                // Complete registration
                if ($this->registrationService) {
                    $this->completeRegistration();
                    return; // The completeRegistration method will handle the redirect
                } else {
                    $this->message = "Registration service not available. Please try again later.";
                    return;
                }
            }
            
            $this->step = min($this->step + 1, $this->totalSteps);
            $this->message = null; // Clear any previous messages
        } catch (\Exception $e) {
            $this->message = "An error occurred: " . $e->getMessage();
        }
    }
    
    public function previousStep()
    {
        $this->step = max($this->step - 1, 1);
        $this->message = null; // Clear any previous messages
    }
    
    protected function registerCustomer()
    {
        if (!$this->registrationService) {
            $this->message = "Registration service not available. Please try again later.";
            return;
        }
        
        $userData = [
            'email' => $this->email,
            'password' => $this->password,
            'phone_number' => $this->phone_number,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'omang_number' => $this->omang_number,
        ];
        
        try {
            $result = $this->registrationService->registerCustomer($userData);
            
            if (!$result['success']) {
                $this->message = $result['message'] ?? 'Registration failed. Please try again.';
                return;
            }
            
            // Log in the newly registered user
            Auth::login($result['user']);
            
            // Redirect to Omang verification
            return redirect()->route('verification.omang');
        } catch (\Exception $e) {
            $this->message = "Registration failed: " . $e->getMessage();
        }
    }
    
    protected function completeRegistration()
    {
        if (!$this->registrationService) {
            $this->message = "Registration service not available. Please try again later.";
            return;
        }
        
        $customerProfile = Auth::user()->customerProfile;
        
        if (!$customerProfile) {
            $this->message = "Customer profile not found. Please start the registration process again.";
            return;
        }
        
        $additionalData = [
            'address' => $this->address,
            'postal_code' => $this->postal_code,
            'city' => $this->city,
            'district' => $this->district,
            'occupation' => $this->occupation,
            'employer' => $this->employer,
            'income_range' => $this->income_range,
        ];
        
        try {
            $result = $this->registrationService->completeRegistration($customerProfile, $additionalData);
            
            if (!$result['success']) {
                $this->message = $result['message'] ?? 'Failed to complete registration. Please try again.';
                return;
            }
            
            // Redirect to success page
            return redirect()->route('registration.success');
        } catch (\Exception $e) {
            $this->message = "Failed to complete registration: " . $e->getMessage();
        }
    }
}