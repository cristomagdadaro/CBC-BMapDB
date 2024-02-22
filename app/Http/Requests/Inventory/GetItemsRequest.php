<?php

namespace App\Http\Requests\Inventory;

use App\Http\Requests\BaseRequest;

class GetItemsRequest extends BaseRequest
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
        return array_merge([
            // add your rules here
        ],config('system_variables.paginate_parameters'));
    }
}
