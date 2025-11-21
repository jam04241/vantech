<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function rules()
    {
        $productId = $this->route('product')?->id;

        return [
            'product_name' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'warranty_period' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'is_used' => 'boolean', // Checkbox to indicate if the product is used
            'price' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/' // Added price validation
        ];
    }

    public function messages()
    {
        return [
            'product_condition.in' => 'Product condition must be either Brand New or Second Hand.',
        ];
    }
}