<?php

namespace App\Http\Requests;

use App\Enums\Permission;
use Illuminate\Contracts\Validation\ValidationRule;

class GetCommoditiesRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!empty(auth()->user()))
            return auth()->user()->hasPermissionTo(Permission::READ_COMMODITY->value) || auth()->user()->isAdmin();

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return array_merge([
            // add your rules here
        ],config('system_variables.paginate_parameters'),
            config('system_variables.filtering_parameters'),
            config('system_variables.appendable_parameters'));
    }
}
