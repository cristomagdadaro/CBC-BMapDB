<?php

namespace App\Http\Requests;

use App\Actions\Fortify\PasswordValidationRules;
use App\Enums\Permission;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Laravel\Fortify\Rules\Password;

class CreateBreederRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasPermissionTo(Permission::CREATE_BREEDER->value) || auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //'user_id' => 'required|exists:users,id',
            'fname' => ['required', 'string', 'max:255'],
            'mname' => ['nullable', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:255'],
            'mobile_no' =>  ['nullable', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'unique:breeders,email,'.$this->id,
            ],
            'affiliation' => ['required', 'exists:institutes,id'],
            //'password' => $this->passwordRules(),
            'geolocation' => 'nullable|exists:loc_cities,id',
        ];
    }

    protected function passwordRules(): array
    {
        return ['required', 'string', new Password, 'confirmed'];
    }
}
