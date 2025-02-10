<?php

namespace App\Http\Requests;

use App\Enums\Permission;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateTWGExpertRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasPermissionTo(Permission::CREATE_TWG_EXPERT->value) || auth()->user()->isAdmin();
    }

    protected function prepareForValidation()
    {
        if (!auth()->user()->isAdmin())
            $this->merge([
                'institution' => auth()->user()->affiliation,
            ]);

        $this->merge([
            'user_id'  => auth()->user()->id
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255', 'unique:twg_expert,name'],
            'position' => ['required', 'string', 'max:255'],
            'educ_level' => ['required', 'string', "in:Doctoral,Master's,Bachelor's"],
            'expertise' => ['required', 'string', 'max:255'],
            'institution' => ['required', 'exists:institutes,id'],
            'research_interest' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'unique:twg_expert,mobile', 'regex:/^09[0-9]{9}$/', 'max:11', 'min:11'],
            'email' => ['required', 'string','email', 'unique:twg_expert,email'],
        ];
    }
}
