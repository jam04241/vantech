<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'role' => 'required|in:Staff,Assistant,Technical,Cashier',
            'gender' => 'required|in:male,female',
        ];
    }

    public function messages()
    {
        return [
           'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'street.required' => 'Street address is required',
            'barangay.required' => 'Barangay is required',
            'city.required' => 'City is required',
            'phone_number.required' => 'Phone number is required',
            'gender.required' => 'Gender is required',
            'role.required' => 'Role is required',
        ];
    }
}