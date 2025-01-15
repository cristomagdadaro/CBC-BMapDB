<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateInstituteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string','required','unique:institutes,name'],
            'inst_type' => ['string','required'],
            'city' => ['string','required'],
            'province' => ['string','required'],
            'region' => ['string','required'],
            'website' => ['string','required','unique:institutes,email'],
            'email' => ['string','required'],
            'phone' => ['string','required'],
        ];
    }
}
