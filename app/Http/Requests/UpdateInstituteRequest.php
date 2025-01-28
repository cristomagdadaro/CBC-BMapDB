<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInstituteRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string','required','unique:institutes,name,' . $this->id],
            'inst_type' => ['string','required'],
            'city' => ['string','required'],
            'province' => ['string','required'],
            'region' => ['string','required'],
            'website' => ['string','required','unique:institutes,email,' . $this->id],
            'email' => ['string','required'],
            'phone' => ['string','required'],
        ];
    }
}
