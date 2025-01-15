<?php

namespace App\Http\Requests;

use App\Actions\Fortify\PasswordValidationRules;
use App\Enums\Permission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Rules\Password;

class UpdateBreederRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasPermissionTo(Permission::UPDATE_BREEDER->value) || auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //'user_id' => 'required|exists:users,id',
            'name' => 'required|string|unique:breeders,name,'.$this->id,
            'affiliation' => 'required|exists:institutes,id',
            'geolocation' => 'nullable|exists:loc_cities,id',
            'phone' => 'nullable|string|unique:breeders,phone,'.$this->id,
            'email' => [
                'required',
                'email',
                'unique:breeders,email,'.$this->id,
            ],
            'password' => $this->passwordRules(),
        ];
    }

    protected function passwordRules(): array
    {
        return ['nullable', 'string', new Password, 'confirmed'];
    }
}
