<?php

namespace App\Http\Requests\Map;

use Illuminate\Foundation\Http\FormRequest;

class GetMapDistributionRequest extends FormRequest
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
            'institute' => 'nullable|string',
            'breeder_type' => 'nullable|string',
            'institute_type' => 'nullable|string',
            'region' => 'nullable|string',
            'province' => 'nullable|string',
            'city' => 'nullable|string',
            'search' => 'nullable|string',
            'group_by' => 'nullable|string|in:region,province,city,institute,breeder_type',
        ];
    }
}
