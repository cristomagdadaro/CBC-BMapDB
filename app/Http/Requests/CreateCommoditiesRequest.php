<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCommoditiesRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:commodities,name',
            'breeder_id' => 'required|integer|exists:breeders,id',
            'scientific_name' => 'required|string|max:255',
            'variety' => 'required|string|max:255',
            'accession' => 'required|string|max:255',
            'germplasm' => 'required|string|max:255',
            'population' => 'required|integer',
            'maturity_period' => 'required|string',
            'yield' => 'required|integer',
            'description' => 'required|string',
            'image' => 'nullable',
        ];
    }
}