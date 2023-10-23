<?php

namespace App\Http\Requests;

class DataTableRequest extends BaseRequest
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
        return [
            'table' => 'required|string',
            'columns' => 'required|string',
            'per_page' => 'required|integer',
            'page' =>'required|integer',
            'order' =>'required|string',
            'order_dir' =>'required|string',
            'filterKey' =>'nullable|string',
            'filterValue' =>'nullable|string',
        ];
    }
}
