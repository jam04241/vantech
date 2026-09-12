<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Sign out anyone whose account stopped being active mid-session.
 *
 * Blocking at login alone is not enough: an owner who deactivates a staff
 * member while they are logged in would otherwise leave that session working
 * until it expired.
 */
class EnsureAccountIsActive
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && !$user->isActive()) {
            $reason = $user->loginBlockedReason() ?? 'Your account is no longer active.';

            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json(['message' => $reason], 401);
            }

            return redirect()->route('login')->withErrors(['username' => $reason]);
        }

        return $next($request);
    }
}
