<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffOnly
{
    /**
     * Handle an incoming request.
     * Only staff users can access. Admins are allowed but staff is restricted.
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

        // Allow both admin and staff
        if ($user->role === 'admin' || $user->role === 'staff') {
            return $next($request);
        }

        // Deny access for other roles
        return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
    }
}
