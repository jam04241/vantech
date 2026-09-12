<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Rules for the owner's edit form.
     *
     * Address is supplied by the applicant at registration and is nullable, so
     * the owner is not forced to retype it to save an unrelated change.
     * Position stays required: assigning it is the owner's job.
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'street' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'phone_number' => 'required|string|max:20',
            'role' => ['required', Rule::in(Employee::ROLES)],
            'gender' => 'required|in:male,female',
        ];
    }

    public function messages()
    {
        return [
           'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'phone_number.required' => 'Phone number is required',
            'gender.required' => 'Gender is required',
            'role.required' => 'Please assign a position to this employee.',
            'role.in' => 'The selected position is invalid.',
        ];
    }
}