<?php

namespace App\Http\Requests;

use App\Enums\Permission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasPermissionTo(Permission::CREATE_APP_ACCOUNT->value) || auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],
            'app_id' => [
                'required',
                'integer',
                'exists:applications,id',
            ],
            'approved_at' => 'nullable|date',
            'accounts_user_id_app_id_unique' => [
                Rule::unique('accounts')
                    ->where(function ($query) {
                        return $query->where('user_id', $this->user_id)
                            ->where('app_id', $this->app_id);
                    }),
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'Please select a user',
            'app_id.required' => 'Please select an application',
        ];
    }
}
