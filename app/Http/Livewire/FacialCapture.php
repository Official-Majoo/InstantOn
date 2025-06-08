<?php

namespace App\Http\Livewire;

use App\Services\FacialComparisonService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FacialCapture extends Component
{
    use WithFileUploads;
    
    public $selfieImage;
    public $capturedImage;
    public $processingStatus = '';
    public $similarityScore = 0;
    public $verificationPassed = false;
    public $verificationSessionId = null;
    public $isCapturing = false;
    
    protected $facialComparisonService;

    
    // Define listeners for Livewire events - compatible with all versions
    protected $listeners = [
        'capture' => 'capture',
        'processVerification' => 'processVerification',
        'retry' => 'retry'
    ];
    
    public function boot(FacialComparisonService $facialComparisonService)
    {
        $this->facialComparisonService = $facialComparisonService;
    }
    
    public function render()
    {
        return view('livewire.facial-capture');
    }
    
    public function updated($name, $value)
    {
        // In older Livewire, we can omit this
    }
    
    public function startCapture()
    {
        $this->reset(['capturedImage', 'processingStatus', 'similarityScore', 'verificationPassed']);
        $this->isCapturing = true;
        
        // For Livewire 3
        if (method_exists($this, 'dispatch')) {
            $this->dispatch('start-camera');
        } 
        // For Livewire 2
        elseif (method_exists($this, 'dispatchBrowserEvent')) {
            $this->dispatchBrowserEvent('start-camera');
        }
        // For Livewire 2 alternative
        elseif (method_exists($this, 'emit')) {
            $this->emit('start-camera');
        }
    }
    
    public function capture($imageData)
    {
        $this->capturedImage = $imageData;
        $this->isCapturing = false;
    }
    
    public function processVerification()
    {
        $this->validate([
            'capturedImage' => 'required',
        ], [
            'capturedImage.required' => 'Please capture a photo first.'
        ]);
        
        $this->processingStatus = 'processing';
        
        try {
            $customerProfile = Auth::user()->customerProfile;
            
            if (!$customerProfile) {
                $this->processingStatus = 'error';
                $this->addError('verification', 'Customer profile not found');
                return;
            }
            
            $result = $this->facialComparisonService->compareFaces(
                $customerProfile, 
                $this->capturedImage
            );
            
            if (!$result['success']) {
                $this->processingStatus = 'error';
                $this->addError('verification', $result['message'] ?? 'Verification failed');
                return;
            }
            
            $this->similarityScore = $result['similarity_score'];
            $this->verificationPassed = $result['passed'];
            $this->verificationSessionId = $result['verification_session_id'];
            $this->processingStatus = 'completed';
            
            // Dispatch appropriate event based on verification result
            if ($this->verificationPassed) {
                // For Livewire 3
                if (method_exists($this, 'dispatch')) {
                    $this->dispatch('verificationPassed', $this->verificationSessionId);
                } 
                // For Livewire 2
                elseif (method_exists($this, 'emit')) {
                    $this->emit('verificationPassed', $this->verificationSessionId);
                }
            } else {
                // For Livewire 3
                if (method_exists($this, 'dispatch')) {
                    $this->dispatch('verificationFailed', $this->similarityScore);
                }
                // For Livewire 2
                elseif (method_exists($this, 'emit')) {
                    $this->emit('verificationFailed', $this->similarityScore);
                }
            }
            
        } catch (\Exception $e) {
            Log::error('Facial verification error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            $this->processingStatus = 'error';
            $this->addError('verification', 'Error processing verification: ' . $e->getMessage());
        }
    }
    
    public function retry()
    {
        Log::info('Retry method called in Livewire component');
        
        // Reset all relevant properties
        $this->reset([
            'capturedImage', 
            'processingStatus', 
            'similarityScore', 
            'verificationPassed',
            'verificationSessionId'
        ]);
        
        // Set capture mode
        $this->isCapturing = true;
        
        // Log the state for debugging
        Log::info('Component state after retry', [
            'isCapturing' => $this->isCapturing,
            'hasImage' => !empty($this->capturedImage),
        ]);
        
        // Dispatch browser event to trigger camera initialization in JS
        // Check which method exists and use it
        
        // For Livewire 3
        if (method_exists($this, 'dispatch')) {
            $this->dispatch('retry-capture', ['timestamp' => time()]);
        } 
        // For Livewire 2
        elseif (method_exists($this, 'dispatchBrowserEvent')) {
            $this->dispatchBrowserEvent('retry-capture', ['timestamp' => time()]);
        }
        // For Livewire 2 alternative
        elseif (method_exists($this, 'emit')) {
            $this->emit('retry-capture', ['timestamp' => time()]);
        }
    }
}