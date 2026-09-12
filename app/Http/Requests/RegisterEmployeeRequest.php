<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * What an applicant supplies when registering.
 *
 * Applicants give their own address. The address columns are nullable, so any
 * part left blank is simply stored as null rather than rejected.
 *
 * Position is the one thing not asked for here: the owner assigns it from the
 * staff record once they review the application.
 */
class RegisterEmployeeRequest extends FormRequest
{
    /**
     * Registration is public: applicants are not logged in yet.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Staff record
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'gender' => 'required|in:male,female',

            // Address. Nullable in the database, so blanks are accepted.
            'street' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',

            // Login account
            'username' => [
                'required',
                'string',
                'min:4',
                'max:255',
                'alpha_dash',
                Rule::unique('users', 'username'),
            ],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'phone_number.required' => 'Phone number is required.',
            'gender.required' => 'Please select your gender.',
            'username.required' => 'Please choose a username.',
            'username.unique' => 'That username is already taken. Please choose another.',
            'username.alpha_dash' => 'Username may only contain letters, numbers, dashes and underscores.',
            'username.min' => 'Username must be at least 4 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }
}
