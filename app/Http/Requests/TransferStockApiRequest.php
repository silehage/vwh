<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferStockApiRequest extends FormRequest
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
            'reference' => ['required'],
            'description' => ['required'],
            'stock_items' => ['required', 'array'],
            'stock_items.*.sku_number' => ['required'],
            'stock_items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
