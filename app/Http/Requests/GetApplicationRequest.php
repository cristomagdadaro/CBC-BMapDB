<?php

namespace App\Http\Requests;

use App\Enums\Permission;
use Illuminate\Contracts\Validation\ValidationRule;

class GetApplicationRequest extends BaseRequest
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
