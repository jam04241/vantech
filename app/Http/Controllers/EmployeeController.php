<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use App\Models\User;
use App\Traits\LogsAuditTrail;
use Illuminate\Http\Request;

/**
 * Owner-facing staff records.
 *
 * Staff are no longer created here - they register themselves and the owner
 * approves them (see RegistrationController and StaffAccountController). What
 * remains is viewing, editing the record, and switching the account on or off.
 */
class EmployeeController extends Controller
{
    use LogsAuditTrail;

    public function show(Request $request)
    {
        $query = Employee::with('user')->latest();

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $like = '%' . $search . '%';

            $query->where(function ($q) use ($like) {
                $q->whereRaw('LOWER(first_name) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(last_name) LIKE ?', [$like])
                    ->orWhereRaw("LOWER(CONCAT(first_name, ' ', last_name)) LIKE ?", [$like])
                    ->orWhereRaw('LOWER(role) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(street) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(barangay) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(city) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(phone_number) LIKE ?', [$like])
                    ->orWhereHas('user', function ($u) use ($like) {
                        $u->whereRaw('LOWER(username) LIKE ?', [$like]);
                    });
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->filled('status')) {
            $status = $request->input('status');

            if ($status === 'no_account') {
                $query->doesntHave('user');
            } else {
                $query->whereHas('user', fn ($u) => $u->where('status', $status));
            }
        }

        $employees = $query->paginate(50)->withQueryString();

        return view('DASHBOARD.staff_record', [
            'employees' => $employees,
            'pendingCount' => Employee::whereHas('user', fn ($u) => $u->where('status', User::STATUS_PENDING))->count(),
            'activeCount' => Employee::whereHas('user', fn ($u) => $u->where('status', User::STATUS_ACTIVE))->count(),
            'inactiveCount' => Employee::whereHas('user', fn ($u) => $u->where('status', User::STATUS_INACTIVE))->count(),
            'searchQuery' => $request->input('search', ''),
            'statusFilter' => $request->input('status', ''),
            'roleFilter' => $request->input('role', ''),
            'roles' => Employee::ROLES,
        ]);
    }

    /**
     * Staff record as JSON, for the edit modal.
     */
    public function edit(Employee $employee)
    {
        return response()->json([
            'success' => true,
            'employee' => $employee->only([
                'id', 'first_name', 'last_name', 'street',
                'barangay', 'city', 'phone_number', 'role', 'gender',
            ]),
        ]);
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        $before = $employee->only(array_keys($request->validated()));

        try {
            $employee->update($request->validated());

            // Keep the login account's name in step with the staff record.
            $employee->user?->update([
                'first_name' => $employee->first_name,
                'last_name' => $employee->last_name,
            ]);

            $this->logUpdateAudit(
                'UPDATE',
                'Staff',
                "Updated staff record for {$employee->full_name}",
                $before,
                $employee->only(array_keys($request->validated())),
                $request
            );

            return redirect()->route('staff.record')->with('success', 'Employee updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating employee: ' . $e->getMessage());
        }
    }
}
