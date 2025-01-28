<?php

namespace App\Http\Requests;

use App\Enums\Permission;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTWGProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasPermissionTo(Permission::UPDATE_TWG_PROJECT->value) || auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'twg_expert_id' => ['required', 'integer', 'exists:twg_expert,id'],
            'title' => ['required', 'string', 'max:255'],
            'objective' => ['required', 'string'],
            'expected_output' => ['required', 'string'],
            'project_leader' => ['required', 'string'],
            'funding_agency' => ['required', 'string'],
            'duration' => ['required', 'string'],
            'status' => ['required', 'string'],
        ];
    }
}
