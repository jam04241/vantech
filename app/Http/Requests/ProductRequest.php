<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Warranty options offered by the product forms.
     *
     * "No Warranty" is a first-class choice: a computer shop sells plenty of items
     * (cables, consumables, most peripherals, second hand stock) with no coverage.
     */
    public const WARRANTY_OPTIONS = [
        Product::NO_WARRANTY,
        '3 days',
        '7 days',
        '10 days',
        '15 days',
        '30 days',
        '6 months',
        '1 year',
        '2 years',
    ];

    public function rules()
    {
        $productId = $this->route('product')?->id;

        return [
            'product_name' => 'required|string|max:255',
            // serial_number is NOT NULL + UNIQUE in the database; enforce both here
            // so a bad submit returns a validation message instead of a 500.
            'serial_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'serial_number')->ignore($productId),
            ],
            // Blank means "no warranty" and is normalised by the Product model.
            'warranty_period' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            // brand_id is NOT NULL in the database.
            'brand_id' => 'required|exists:brands,id',
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
            'brand_id.required' => 'Please select a product brand.',
            'brand_id.exists' => 'The selected brand is invalid.',
            'price.required' => 'Product price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price must be at least 0.01.',
            'price.regex' => 'Price format is invalid. Use format like 100.00',
        ];
    }
}
