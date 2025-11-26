<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'payment_method' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.total_price' => 'required|numeric|min:0',
            'items.*.serial_number' => 'required|string',
            'items.*.order_date' => 'required|date',
            'items.*.status' => 'required|string',
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
            'customer_id.required' => 'Customer is required',
            'customer_id.exists' => 'Selected customer does not exist',
            'payment_method.required' => 'Payment method is required',
            'amount.required' => 'Amount is required',
            'amount.numeric' => 'Amount must be a valid number',
            'items.required' => 'At least one item is required',
            'items.*.product_id.required' => 'Product ID is required for each item',
            'items.*.unit_price.required' => 'Unit price is required for each item',
            'items.*.quantity.required' => 'Quantity is required for each item',
            'items.*.total_price.required' => 'Total price is required for each item',
        ];
    }
}
