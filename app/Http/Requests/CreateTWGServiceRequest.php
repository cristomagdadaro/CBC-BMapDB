<?php

namespace App\Http\Requests;

use App\Models\TWGExpert;
use Illuminate\Foundation\Http\FormRequest;

class CreateTWGServiceRequest extends FormRequest
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
            'twg_expert_id' => ['required', 'integer', 'exists:'.((new TWGExpert())->getTableName()).',id'],
            'type' => ['required', 'string'],
            'purpose' => ['required', 'string'],
            'direct_beneficiaries' => ['nullable', 'string'],
            'indirect_beneficiaries' => ['nullable', 'string'],
            'officer_in_charge' => ['required', 'string'],
            'cost' => ['required'],
        ];
    }
}
