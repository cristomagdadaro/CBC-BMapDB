<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBreederRequest extends FormRequest
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
            'name' => 'required|string|unique:breeders,name,'.$this->id,
            'agency' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string|unique:breeders,phone,'.$this->id,
            'email' => [
                'required',
                'email',
                'unique:breeders,email,'.$this->id,
            ],
        ];
    }
}
