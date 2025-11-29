<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOnly
{
    /**
     * Handle an incoming request.
     * Only admin users can access. Staff users are redirected to admin verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // If user is admin, allow access
        if ($user->role === 'admin') {
            return $next($request);
        }

        // If user is staff, check if admin password has been verified
        if ($user->role === 'staff') {
            // Check if admin verification session exists and is recent (within 1 hour)
            if ($request->session()->has('admin_verified')) {
                $verifiedAt = $request->session()->get('admin_verified_at');
                if ($verifiedAt && now()->diffInMinutes($verifiedAt) < 60) {
                    return $next($request);
                }
            }

            // Store the intended URL and show admin verification modal
            $request->session()->put('intended_url', $request->fullUrl());
            $request->session()->put('show_admin_verification', true);

            return redirect()->route('dashboard')->with('show_admin_verification', true);
        }

        // Deny access for other roles
        return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
    }
}
