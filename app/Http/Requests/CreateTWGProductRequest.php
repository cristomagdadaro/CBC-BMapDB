<?php

namespace App\Http\Requests;

use App\Models\TWGExpert;
use App\Models\TWGProduct;
use Illuminate\Foundation\Http\FormRequest;

class CreateTWGProductRequest extends FormRequest
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
            'twg_expert_id' => ['required', 'integer', 'exists:'.(new TWGExpert())->getTableName().',id'],
            'name' => ['required', 'string', 'max:255', 'unique:'.(new TWGProduct())->getTableName().',name'],
            'brand' => ['nullable', 'string', 'max:255'],
            'purpose' => ['required', 'string'],
            'cost' => ['required', 'numeric', 'min:0'],
        ];
    }
}
