<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
            'dr_receipt_id' => 'nullable|exists:dr_transactions,id',
            'customer_id' => 'required|exists:customers,id',
            'service_type_id' => 'required|exists:service_types,id',
            'type' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'description' => 'required|string',
            'action' => 'nullable|string',
            'status' => 'required|string|max:255',
            'total_price' => 'required|numeric|min:0',
            'date_in' => 'nullable|date_format:Y-m-d',
            'date_out' => 'nullable|date_format:Y-m-d',
        ];
    }

    /**
     * Get custom error messages for validation rules
     */
    public function messages()
    {
        return [
            'customer_id.required' => 'Customer is required. Please select a customer from the dropdown.',
            'customer_id.exists' => 'The selected customer does not exist.',
            'service_type_id.required' => 'Service type is required. Please select a service type.',
            'service_type_id.exists' => 'The selected service type does not exist.',
            'type.required' => 'Type of item is required.',
            'type.string' => 'Type of item must be a valid text.',
            'type.max' => 'Type of item cannot exceed 255 characters.',
            'brand.string' => 'Brand must be a valid text.',
            'brand.max' => 'Brand cannot exceed 255 characters.',
            'model.string' => 'Model must be a valid text.',
            'model.max' => 'Model cannot exceed 255 characters.',
            'description.required' => 'Description is required.',
            'description.string' => 'Description must be a valid text.',
            'action.string' => 'Action must be a valid text.',
            'status.required' => 'Status is required.',
            'status.string' => 'Status must be a valid text.',
            'status.max' => 'Status cannot exceed 255 characters.',
            'total_price.required' => 'Total price is required.',
            'total_price.numeric' => 'Total price must be a number.',
            'total_price.min' => 'Total price must be at least 0.',
            'date_in.date_format' => 'Date in must be in Y-m-d format.',
            'date_out.date_format' => 'Date out must be in Y-m-d format.',
        ];
    }
}
