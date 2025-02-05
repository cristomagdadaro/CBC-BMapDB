<?php

namespace App\Http\Requests;

use App\Enums\Permission;
use App\Models\TWGExpert;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTWGServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasPermissionTo(Permission::UPDATE_TWG_SERVICE->value) || auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'institution' => ['required', 'exists:institutes,id'],
            'type' => ['required', 'string'],
            'purpose' => ['required', 'string'],
            'direct_beneficiaries' => ['nullable', 'string'],
            'indirect_beneficiaries' => ['nullable', 'string'],
            'officer_in_charge' => ['required', 'exists:twg_expert,id'],
            'cost' => ['required'],
        ];
    }
}
