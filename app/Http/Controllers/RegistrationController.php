<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterEmployeeRequest;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Self-service registration for employees applying to work at the shop.
 *
 * A registration creates two linked rows: the staff record the owner manages,
 * and a login account that stays pending until the owner approves it. Nothing
 * here grants access - approval happens in StaffAccountController.
 */
class RegistrationController extends Controller
{
    public function create(): View
    {
        return view('LOGIN_FORM.register');
    }

    public function store(RegisterEmployeeRequest $request): RedirectResponse
    {
        $data = $request->validated();

        try {
            DB::transaction(function () use ($data) {
                // Position is left null on purpose: the owner assigns it from
                // the staff record when reviewing the application.
                $employee = Employee::create([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'street' => $data['street'] ?? null,
                    'barangay' => $data['barangay'] ?? null,
                    'city' => $data['city'] ?? null,
                    'phone_number' => $data['phone_number'],
                    'gender' => $data['gender'],
                ]);

                User::create([
                    'first_name' => $data['first_name'],
                    'middle_name' => $data['middle_name'] ?? null,
                    'last_name' => $data['last_name'],
                    'username' => $data['username'],
                    // Hashed by the model's 'password' cast.
                    'password' => $data['password'],
                    // Permission level, not job title. Applicants are never admins.
                    'role' => 'staff',
                    'status' => User::STATUS_PENDING,
                    'employee_id' => $employee->id,
                ]);
            });
        } catch (\Throwable $e) {
            Log::error('Employee registration failed', [
                'username' => $data['username'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return back()
                ->with('error', 'We could not complete your registration. Please try again.')
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        // Deliberately no auto-login: the account is not approved yet.
        return redirect()
            ->route('register')
            ->with('registered', true)
            ->with('success', 'Registration submitted. The owner will review your application '
                . 'and activate your account. You will be able to log in once it is approved.');
    }
}
