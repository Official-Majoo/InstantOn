<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyOmangStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
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
        
        if ($customerProfile->verification_status !== 'verified') {
            return redirect()->route('verification.omang')
                ->with('error', 'Please complete Omang verification first.');
        }
        
        return $next($request);
    }
}
