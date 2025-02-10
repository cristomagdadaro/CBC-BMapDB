<?php

namespace App\Http\Requests;

use App\Enums\Permission;
use Illuminate\Foundation\Http\FormRequest;

class GetTWGProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!empty(auth()->user()))
            return auth()->user()->hasPermissionTo(Permission::READ_TWG_PROJECT->value) || auth()->user()->isAdmin();

        return true;

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return array_merge([
            // add your rules here
        ],config('system_variables.paginate_parameters'));
    }
}
