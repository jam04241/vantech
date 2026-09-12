<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Traits\LogsAuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Owner-side control of staff login accounts.
 *
 * Approving a registration and switching an account off are the only two
 * operations. Accounts are never deleted, so the staff record and its audit
 * history stay intact.
 */
class StaffAccountController extends Controller
{
    use LogsAuditTrail;

    /**
     * Approve a pending registration, or switch a deactivated account back on.
     */
    public function activate(Request $request, Employee $employee)
    {
        $account = $employee->user;

        if (!$account) {
            return $this->respond($request, false, 'This staff member has no login account to activate.');
        }

        if ($this->isSelf($account)) {
            return $this->respond($request, false, 'You cannot change your own account status.');
        }

        if ($account->isActive()) {
            return $this->respond($request, false, 'This account is already active.');
        }

        $wasPending = $account->isPending();

        $account->update([
            'status' => User::STATUS_ACTIVE,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        $verb = $wasPending ? 'Approved registration for' : 'Reactivated account for';
        $this->logUpdateAudit(
            'UPDATE',
            'Staff',
            "{$verb} {$employee->full_name} ({$account->username})",
            ['status' => $wasPending ? User::STATUS_PENDING : User::STATUS_INACTIVE],
            ['status' => User::STATUS_ACTIVE],
            $request
        );

        return $this->respond($request, true, $wasPending
            ? "{$employee->full_name}'s account has been approved and can now log in."
            : "{$employee->full_name}'s account has been reactivated.");
    }

    /**
     * Switch an account off. The holder is signed out on their next request by
     * the EnsureAccountIsActive middleware.
     */
    public function deactivate(Request $request, Employee $employee)
    {
        $account = $employee->user;

        if (!$account) {
            return $this->respond($request, false, 'This staff member has no login account to deactivate.');
        }

        if ($this->isSelf($account)) {
            return $this->respond($request, false, 'You cannot deactivate your own account.');
        }

        // Guard against locking every owner out of the system.
        if ($account->isAdmin() && User::where('role', 'admin')->where('status', User::STATUS_ACTIVE)->count() <= 1) {
            return $this->respond($request, false, 'This is the last active owner account and cannot be deactivated.');
        }

        if ($account->status === User::STATUS_INACTIVE) {
            return $this->respond($request, false, 'This account is already deactivated.');
        }

        $previous = $account->status;
        $account->update(['status' => User::STATUS_INACTIVE]);

        $this->logUpdateAudit(
            'UPDATE',
            'Staff',
            "Deactivated account for {$employee->full_name} ({$account->username})",
            ['status' => $previous],
            ['status' => User::STATUS_INACTIVE],
            $request
        );

        return $this->respond($request, true, "{$employee->full_name}'s account has been deactivated.");
    }

    private function isSelf(User $account): bool
    {
        return Auth::id() === $account->id;
    }

    private function respond(Request $request, bool $ok, string $message)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => $ok, 'message' => $message], $ok ? 200 : 422);
        }

        return back()->with($ok ? 'success' : 'error', $message);
    }
}
