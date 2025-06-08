<?php

// app/Http/Middleware/CheckRegistrationStep.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRegistrationStep
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $step
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $step)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        $customerProfile = $user->customerProfile;
        
        if (!$customerProfile) {
            return redirect()->route('registration.start')
                ->with('error', 'Please complete your registration first.');
        }
        
        switch ($step) {
            case 'omang':
                // Always allow access to Omang verification
                break;
                
            case 'documents':
                if ($customerProfile->verification_status !== 'verified') {
                    return redirect()->route('verification.omang')
                        ->with('error', 'Please complete Omang verification first.');
                }
                break;
                
            case 'facial':
                if ($customerProfile->verification_status !== 'verified') {
                    return redirect()->route('verification.omang')
                        ->with('error', 'Please complete Omang verification first.');
                }
                
                // Check if documents are uploaded
                $requiredDocumentTypes = ['omang_front', 'omang_back', 'proof_of_address'];
                $documents = $customerProfile->documents()->whereIn('document_type', $requiredDocumentTypes)->get();
                $documentTypes = $documents->pluck('document_type')->toArray();
                
                foreach ($requiredDocumentTypes as $type) {
                    if (!in_array($type, $documentTypes)) {
                        return redirect()->route('verification.documents')
                            ->with('error', 'Please upload all required documents first.');
                    }
                }
                break;
                
            case 'additional':
                if ($customerProfile->verification_status !== 'verified') {
                    return redirect()->route('verification.omang')
                        ->with('error', 'Please complete Omang verification first.');
                }
                
                // Check if documents are uploaded
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
                break;
                
            default:
                return redirect()->route('dashboard');
        }
        
        return $next($request);
    }
}