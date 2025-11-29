<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('LOGIN_FORM.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Verify admin password for staff access to restricted pages.
     */
    public function verifyAdminPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'admin_password' => 'required|string',
            'intended_url' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Check if user is staff
        if ($user->role !== 'staff') {
            return back()->withErrors(['admin_password' => 'Only staff can verify admin password.']);
        }

        // Get any admin user to verify password against
        $admin = User::where('role', 'admin')->first();

        if (!$admin || !Hash::check($request->admin_password, $admin->password)) {
            return back()->withErrors(['admin_password' => 'Invalid admin password.']);
        }

        // Set session flag for admin verification
        $request->session()->put('admin_verified', true);
        $request->session()->put('admin_verified_at', now());

        // Get intended URL from request or session, default to dashboard
        $intendedUrl = $request->input('intended_url')
            ?? $request->session()->pull('intended_url', route('dashboard'));

        return redirect($intendedUrl)->with('success', 'Admin verification successful. You can now access this page.');
    }
}
