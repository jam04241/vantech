<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceReplacementRequest extends FormRequest
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
            'service_id' => 'required|exists:services,id',
            'item_name' => 'required|string|max:255',
            'old_item_condition' => 'required|string',
            'new_item' => 'required|string',
            'new_item_price' => 'required|numeric|min:0',
            'new_item_warranty' => 'nullable|string|max:255',
            'is_disabled' => 'nullable|in:0,1',  // Accept 0 or 1
        ];
    }
}
