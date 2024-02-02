<?php

namespace App\Http\Requests\Inventory;

use App\Http\Requests\BaseRequest;

class CreateTransactionRequest extends BaseRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'item_id' => 'required|exists:items,id',
            'barcode' => 'required|string',
            'transac_type' => 'required|in:in,out',
            'quantity' => 'required|numeric',
            'unit_price' => 'required|numeric',
            'unit' => 'required|string',
            'total_cost' => 'required|numeric',
            'personnel_id' => 'required|exists:personnels,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'expiration' => 'nullable|date',
            'remarks' => 'nullable|string',
        ];
    }
}
