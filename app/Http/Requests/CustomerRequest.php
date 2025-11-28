<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
      return [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'gender' => 'required|string|max:255',
        'contact_no' => 'required|string|max:255',
        'street' => 'required|string|max:255',
        'brgy' => 'required|string|max:255',
        'city_province' => 'required|string|max:255',
    ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'contact_no.required' => 'Contact number is required.',
        ];
    }
}