<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTWGProductRequest extends FormRequest
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
            'twg_expert_id' => ['required', 'integer', 'exists:twg_expert,id'],
            'name' => ['required', 'string', 'max:255', 'unique:twg_products,name'],
            'brand' => ['nullable', 'string', 'max:255'],
            'purpose' => ['required', 'string'],
            'cost' => ['required', 'numeric', 'min:0'],
        ];
    }
}
