<?php

namespace App\Http\Requests;

use App\Enums\Permission;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTWGExpertRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasPermissionTo(Permission::UPDATE_TWG_EXPERT->value) || auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //'user_id' => ['required', 'integer', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255', 'unique:twg_expert,name,'. $this->get('id')],
            'position' => ['required', 'string', 'max:255'],
            'educ_level' => ['required', 'string', "in:Doctoral,Master's,Bachelor's"],
            'expertise' => ['required', 'string', 'max:255'],
            'research_interest' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'unique:twg_expert,mobile,'. $this->get('id'), 'regex:/^09[0-9]{9}$/', 'max:11', 'min:11'],
            'email' => ['required', 'string','email', 'unique:twg_expert,email,'. $this->get('id')],
        ];
    }
}
