<?php

namespace App\Http\Requests\Map;

use Illuminate\Foundation\Http\FormRequest;

class GetOrbitItemsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data_type' => 'required|string|in:commodities,breeders',
            'city_ids' => 'required|string',
            'limit' => 'nullable|integer|min:1|max:24',
        ];
    }
}
