<?php

namespace App\Http\Requests;

use App\Enums\Permission;
use DB;
use Illuminate\Contracts\Validation\ValidationRule;
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
     * @return array<string, ValidationRule|array|string>
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
            'role' => [
                'required',
                'integer',
                'exists:roles,id',
            ],
            'approved_at' => 'nullable|date',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $userId = $this->input('user_id');
            $appId = $this->input('app_id');

            if ($userId && $appId) {
                $exists = DB::table('accounts')
                    ->where('user_id', $userId)
                    ->where('app_id', $appId)
                    ->exists();

                if ($exists) {
                    $validator->errors()->add(
                        'accounts_user_id_app_id_unique',
                        'You already have an account/pending request for this application'
                    );
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Please select a user',
            'app_id.required' => 'Please select an application',
            'approved_at.date' => 'Invalid date format',
        ];
    }
}
