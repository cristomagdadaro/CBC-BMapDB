<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetDataViewsRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge([
            'user_id' => 'sometimes|exists:users,id',
            'model' => 'string|sometimes',
            'model_id' => 'numeric|sometimes',
            'visibility_guard' => 'string|sometimes'
        ],config('system_variables.paginate_parameters'),
            config('system_variables.filtering_parameters'),
            config('system_variables.appendable_parameters'));
    }
}
