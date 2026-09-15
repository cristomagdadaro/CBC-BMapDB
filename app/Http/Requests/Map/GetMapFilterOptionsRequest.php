<?php

namespace App\Http\Requests\Map;

use Illuminate\Foundation\Http\FormRequest;

class GetMapFilterOptionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data_type' => 'required|string|in:commodities,breeders,institutes',
        ];
    }
}
