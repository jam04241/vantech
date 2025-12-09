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
            'price' => 'nullable|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/', // Optional - only for price edit modal
            'product_condition' => 'nullable|string|in:Brand New,Second Hand' // Product condition
        ];
    }

    public function messages()
    {
        return [
            'serial_number.required' => 'Serial number is required to register the product.',
            'serial_number.unique' => 'This serial number is already registered in the system. Please use a different serial number.',
            'product_name.required' => 'Product name is required.',
            'category_id.required' => 'Please select a product category.',
            'category_id.exists' => 'The selected category is invalid.',
            'price.required' => 'Product price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price must be at least 0.01.',
            'price.regex' => 'Price format is invalid. Use format like 100.00',
        ];
    }
}
