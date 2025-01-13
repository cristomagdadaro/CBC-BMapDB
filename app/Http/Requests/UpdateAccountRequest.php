<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $accountId = $this->route('id'); // assuming the route parameter is named 'account'

        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::unique('accounts')->where(function ($query) use ($accountId) {
                    return $query->where('app_id', $this->app_id)->where('id', '!=', $accountId);
                })->ignore($accountId)
            ],
            'app_id' => [
                'required',
                'integer',
                'exists:applications,id',
                Rule::unique('accounts')->where(function ($query) use ($accountId) {
                    return $query->where('user_id', $this->user_id)->where('id', '!=', $accountId);
                })->ignore($accountId)
            ],
            'approved_at' => 'nullable|date',
            'permissions' => 'array|nullable',
            'role' => 'nullable|array',
            'role.*' => 'required|integer|exists:roles,id',
        ];
    }
}
