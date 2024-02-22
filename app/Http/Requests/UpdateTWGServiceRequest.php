<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTWGServiceRequest extends FormRequest
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
            'twg_expert_id' => ['required', 'exists:twg_expert,user_id'],
            'type' => ['required', 'string'],
            'purpose' => ['required', 'string'],
            'direct_beneficiaries' => ['required', 'string'],
            'indirect_beneficiaries' => ['required', 'string'],
            'officer_in_charge' => ['required', 'string'],
            'cost' => ['required', 'numeric'],
        ];
    }
}
