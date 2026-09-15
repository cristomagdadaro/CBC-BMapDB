<?php

namespace App\Http\Requests\Map;

use Illuminate\Foundation\Http\FormRequest;

class GetMapDataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data_type' => 'required|string|in:commodities,breeders,institutes',
            'filter_by' => 'nullable|string|in:commodity,city,province,region,institute',
            'commodity' => 'nullable|string',
            'commodities' => 'nullable|string',
            'institute' => 'nullable|string',
            'breeder_type' => 'nullable|string',
            'institute_type' => 'nullable|string',
            'region' => 'nullable|string',
            'regions' => 'nullable|string',
            'province' => 'nullable|string',
            'provinces' => 'nullable|string',
            'city' => 'nullable|string|numeric',
            'cities' => 'nullable|string|numeric',
            'search' => 'nullable|string',
        ];
    }
}
