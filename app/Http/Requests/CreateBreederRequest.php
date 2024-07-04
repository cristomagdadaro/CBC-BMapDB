<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBreederRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|unique:breeders,name',
            'agency' => 'required|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|unique:breeders,phone',
            'email' => 'required|email|unique:breeders,email',

        ];
    }
}
