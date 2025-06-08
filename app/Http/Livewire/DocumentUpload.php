<?php

namespace App\Http\Livewire;

use App\Services\RegistrationService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class DocumentUpload extends Component
{
    use WithFileUploads;
    
    public $omangFront;
    public $omangBack;
    public $proofOfAddress;
    public $uploadProgress = [
        'omang_front' => false,
        'omang_back' => false,
        'proof_of_address' => false,
    ];
    public $replacingDocument = [
        'omang_front' => false,
        'omang_back' => false,
        'proof_of_address' => false,
    ];
    
    protected $registrationService;
    
    protected $rules = [
        'omangFront' => 'required|image|max:5120', // 5MB max
        'omangBack' => 'required|image|max:5120',
        'proofOfAddress' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
    ];
    
    protected $messages = [
        'omangFront.required' => 'The front of your Omang is required.',
        'omangBack.required' => 'The back of your Omang is required.',
        'proofOfAddress.required' => 'Proof of address is required.',
        '*.image' => 'The file must be an image.',
        '*.file' => 'A valid file is required.',
        '*.max' => 'The file may not be larger than 5MB.',
    ];
    
    public function boot(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }
    
    public function render()
    {
        $customerProfile = Auth::user()->customerProfile;
        $documents = $customerProfile ? $customerProfile->documents : collect();
        
        return view('livewire.document-upload', [
            'documents' => $documents,
        ]);
    }
    
    public function toggleReplaceMode($documentType, $value = true)
    {
        $this->replacingDocument[$documentType] = $value;
        
        // If we're canceling replacement, reset the file input
        if ($value === false) {
            if ($documentType === 'omang_front') {
                $this->reset('omangFront');
            } elseif ($documentType === 'omang_back') {
                $this->reset('omangBack');
            } elseif ($documentType === 'proof_of_address') {
                $this->reset('proofOfAddress');
            }
        }
    }
    
    public function uploadOmangFront()
    {
        $this->validate([
            'omangFront' => 'required|image|max:5120',
        ]);
        
        $customerProfile = Auth::user()->customerProfile;
        
        if (!$customerProfile) {
            $this->addError('upload', 'Customer profile not found');
            return;
        }
        
        $result = $this->registrationService->uploadDocument($customerProfile, [
            'file' => $this->omangFront,
            'document_type' => 'omang_front',
        ]);
        
        if ($result['success']) {
            $this->uploadProgress['omang_front'] = true;
            $this->replacingDocument['omang_front'] = false; // Reset replacing flag
            // Replace emit with dispatch for Livewire 3.x
            $this->dispatch('documentUploaded', type: 'omang_front');
        } else {
            $this->addError('upload', $result['message']);
        }
        
        $this->reset('omangFront');
    }
    
    public function uploadOmangBack()
    {
        $this->validate([
            'omangBack' => 'required|image|max:5120',
        ]);
        
        $customerProfile = Auth::user()->customerProfile;
        
        if (!$customerProfile) {
            $this->addError('upload', 'Customer profile not found');
            return;
        }
        
        $result = $this->registrationService->uploadDocument($customerProfile, [
            'file' => $this->omangBack,
            'document_type' => 'omang_back',
        ]);
        
        if ($result['success']) {
            $this->uploadProgress['omang_back'] = true;
            $this->replacingDocument['omang_back'] = false; // Reset replacing flag
            // Replace emit with dispatch for Livewire 3.x
            $this->dispatch('documentUploaded', type: 'omang_back');
        } else {
            $this->addError('upload', $result['message']);
        }
        
        $this->reset('omangBack');
    }
    
    public function uploadProofOfAddress()
    {
        $this->validate([
            'proofOfAddress' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);
        
        $customerProfile = Auth::user()->customerProfile;
        
        if (!$customerProfile) {
            $this->addError('upload', 'Customer profile not found');
            return;
        }
        
        $result = $this->registrationService->uploadDocument($customerProfile, [
            'file' => $this->proofOfAddress,
            'document_type' => 'proof_of_address',
        ]);
        
        if ($result['success']) {
            $this->uploadProgress['proof_of_address'] = true;
            $this->replacingDocument['proof_of_address'] = false; // Reset replacing flag
            // Replace emit with dispatch for Livewire 3.x
            $this->dispatch('documentUploaded', type: 'proof_of_address');
        } else {
            $this->addError('upload', $result['message']);
        }
        
        $this->reset('proofOfAddress');
    }
    
    public function checkAllUploaded()
    {
        $customerProfile = Auth::user()->customerProfile;
        
        if (!$customerProfile) {
            return false;
        }
        
        $documents = $customerProfile->documents;
        
        $this->uploadProgress = [
            'omang_front' => $documents->where('document_type', 'omang_front')->count() > 0,
            'omang_back' => $documents->where('document_type', 'omang_back')->count() > 0,
            'proof_of_address' => $documents->where('document_type', 'proof_of_address')->count() > 0,
        ];
        
        return !in_array(false, $this->uploadProgress);
    }
}