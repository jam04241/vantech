<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DRTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'receipt_no' => 'required|string|max:255|unique:dr_transactions,receipt_no',
            'type' => 'required|string|in:purchase,acknowledgement,service_completed',
            'total_sum' => 'required|numeric|min:0',
        ];
    }
}
