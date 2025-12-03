<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AuditLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Only admin users can create audit logs manually
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'action' => 'required|string|in:CREATE,UPDATE,DELETE,LOGIN,LOGOUT,VIEW',
            'module' => 'required|string|in:Authentication,POS,Inventory,Services,Customer,Supplier,Staff,Admin',
            'description' => 'required|string|max:500',
            'changes' => 'nullable|json',
            'ip_address' => 'nullable|ip',
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
            'user_id.required' => 'User ID is required',
            'user_id.exists' => 'The selected user does not exist',
            'action.required' => 'Action is required',
            'action.in' => 'Action must be one of: CREATE, UPDATE, DELETE, LOGIN, LOGOUT, VIEW',
            'module.required' => 'Module is required',
            'module.in' => 'Module must be one of: Authentication, POS, Inventory, Services, Customer, Supplier, Staff, Admin',
            'description.required' => 'Description is required',
            'description.max' => 'Description cannot exceed 500 characters',
            'changes.json' => 'Changes must be valid JSON',
            'ip_address.ip' => 'IP address must be a valid IP',
        ];
    }
}
