<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CreateDataViewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'uuid' => $this->uuid ?? Str::uuid()->toString(),
            'user_account_id' => auth()->user()->id,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'uuid' => 'nullable|string',  // UUID can be nullable, and if not provided, it will be generated
            'user_account_id' => 'required|exists:users,id',
            'model' => 'required|string',
            'columns' => 'required|string',
            'visibility_guard' => 'required|string',
            'data_views_user_account_id_model_visibility_guard_unique' => [
                'unique:data_views,user_account_id,NULL,uuid,model,' . $this->model . ',visibility_guard,' . $this->visibility_guard
            ],
        ];
    }
}
