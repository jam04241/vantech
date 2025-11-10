<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'supplier_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'sometimes|in:active,inactive',
        ];
    }

    public function messages()
    {
        return [
            'supplier_name.required' => 'Supplier name is required',
            'company_name.required' => 'Company name is required',
            'contact_phone.required' => 'Contact phone is required',
        ];
    }
}