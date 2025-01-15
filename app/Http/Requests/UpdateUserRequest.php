<?php

namespace App\Http\Requests;

use App\Enums\Permission;
use Illuminate\Foundation\Http\FormRequest;
use Laravel\Fortify\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasPermissionTo(Permission::UPDATE_USER->value) || auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'fname' => ['required', 'string', 'max:255'],
            'mname' => ['nullable', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:255'],
            'mobile_no' =>  ['nullable', 'string', 'max:255'],
            'affiliation' => ['required', 'exists:institutes,id'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$this->id],
            'email_verified_at' =>  ['nullable', 'date'],
            'password' => $this->passwordRules(),
        ];
    }

    protected function passwordRules(): array
    {
        return ['sometimes', 'string', new Password, 'confirmed'];
    }
}
