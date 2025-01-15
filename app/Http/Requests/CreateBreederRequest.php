<?php

namespace App\Http\Requests;

use App\Actions\Fortify\PasswordValidationRules;
use App\Enums\Permission;
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //'user_id' => 'required|exists:users,id',
            'name' => 'required|string|unique:breeders,name',
            'affiliation' => 'required|exists:institutes,id',
            'geolocation' => 'nullable|exists:loc_cities,id',
            'phone' => 'nullable|string|unique:breeders,phone',
            'email' => 'required|email|unique:breeders,email',
            'password' => $this->passwordRules(),
        ];
    }

    protected function passwordRules(): array
    {
        return ['required', 'string', new Password, 'confirmed'];
    }
}
