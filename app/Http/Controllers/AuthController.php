<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

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
            $user = Auth::user();
            $request->session()->regenerate();

            // Log login to audit logs
            $this->logLogin($user, $request);

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
        $user = Auth::user();

        // Log logout before destroying session
        $this->logLogout($user, $request);

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

    /**
     * Log user login to audit logs
     *
     * @param User $user
     * @param Request $request
     */
    private function logLogin(User $user, Request $request): void
    {
        try {
            $ipAddress = $request->ip();
            $description = "{$user->first_name} {$user->last_name} logged in";
            $changes = json_encode([
                'username' => $user->username,
                'role' => $user->role,
                'ip_address' => $ipAddress,
                'login_time' => now()->format('Y-m-d H:i:s')
            ]);

            // Try using stored procedure first (works for both MySQL and SQL Server)
            try {
                $this->callStoredProcedure('sp_insert_audit_log', [
                    $user->id,
                    'LOGIN',
                    'Authentication',
                    $description,
                    $changes
                ]);
            } catch (\Exception $e) {
                // Fallback to direct database insert if stored procedure fails
                AuditLog::create([
                    'user_id' => $user->id,
                    'action' => 'LOGIN',
                    'module' => 'Authentication',
                    'description' => $description,
                    'changes' => $changes,
                    'ip_address' => $ipAddress
                ]);
            }
        } catch (\Exception $e) {
            // Log error but don't block authentication
            Log::error('Failed to log user login: ' . $e->getMessage());
        }
    }

    /**
     * Log user logout to audit logs
     *
     * @param User $user
     * @param Request $request
     */
    private function logLogout(User $user, Request $request): void
    {
        try {
            $ipAddress = $request->ip();

            // Calculate session duration if available
            $sessionStartTime = session('login_time') ?? now();
            $sessionDuration = now()->diffInMinutes($sessionStartTime);
            $durationFormatted = $this->formatSessionDuration($sessionDuration);

            $description = "{$user->first_name} {$user->last_name} logged out (Session: {$durationFormatted})";
            $changes = json_encode([
                'username' => $user->username,
                'role' => $user->role,
                'ip_address' => $ipAddress,
                'logout_time' => now()->format('Y-m-d H:i:s'),
                'session_duration_minutes' => $sessionDuration
            ]);

            // Try using stored procedure first (works for both MySQL and SQL Server)
            try {
                $this->callStoredProcedure('sp_insert_audit_log', [
                    $user->id,
                    'LOGOUT',
                    'Authentication',
                    $description,
                    $changes
                ]);
            } catch (\Exception $e) {
                // Fallback to direct database insert if stored procedure fails
                AuditLog::create([
                    'user_id' => $user->id,
                    'action' => 'LOGOUT',
                    'module' => 'Authentication',
                    'description' => $description,
                    'changes' => $changes,
                    'ip_address' => $ipAddress
                ]);
            }
        } catch (\Exception $e) {
            // Log error but don't block logout
            Log::error('Failed to log user logout: ' . $e->getMessage());
        }
    }

    /**
     * Call stored procedure with parameters - Compatible with MySQL and SQL Server
     *
     * @param string $procedureName
     * @param array $parameters
     */
    private function callStoredProcedure(string $procedureName, array $parameters): void
    {
        // Get database driver
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            // MySQL syntax
            $placeholders = implode(',', array_fill(0, count($parameters), '?'));
            DB::statement("CALL $procedureName($placeholders)", $parameters);
        } elseif ($driver === 'sqlsrv') {
            // SQL Server syntax
            $placeholders = collect($parameters)
                ->map(fn($param, $index) => "@param" . ($index + 1))
                ->implode(',');

            // Build the SQL Server EXEC statement
            $sql = "EXEC $procedureName";
            if (!empty($parameters)) {
                $sql .= " " . $placeholders;
            }

            DB::statement($sql, $parameters);
        }
    }

    /**
     * Format session duration in human-readable format
     *
     * @param int $minutes
     * @return string
     */
    private function formatSessionDuration(int $minutes): string
    {
        if ($minutes < 1) {
            return 'less than 1m';
        }

        if ($minutes < 60) {
            return $minutes . 'm';
        }

        $hours = intval($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($remainingMinutes == 0) {
            return $hours . 'h';
        }

        return $hours . 'h ' . $remainingMinutes . 'm';
    }
}
