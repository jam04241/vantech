<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseDetailsRequest extends FormRequest
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

            'product_id' => 'nullable|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'bundle_id' => 'nullable|exists:bundles,id',
            'quantity_ordered' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'order_date' => 'required|date',
            'status' => ['required', 'in:pending,received,cancelled'],
            
        ];
    }
}
