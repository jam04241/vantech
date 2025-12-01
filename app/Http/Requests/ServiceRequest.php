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
}
