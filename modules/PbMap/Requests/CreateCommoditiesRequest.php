<?php

namespace Modules\PbMap\Requests;

use App\Enums\Permission;
use Illuminate\Contracts\Validation\ValidationRule;
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

    protected function prepareForValidation()
    {
        if (!auth()->user()->isAdmin())
            $this->merge([
                'institution' => auth()->user()->affiliation,
            ]);

        $this->merge([
            'user_id'  => auth()->user()->id,
            // Auto-approve if created by admin; otherwise keep pending (null)
            'approved_at' => auth()->user()->isAdmin() ? now() : null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'breeder_id' => 'required|integer|exists:breeders,id',
            'scientific_name' => 'required|string|max:255',
            'accession' => 'required|string|max:255',
            'yield' => 'required|numeric',
            'description' => 'nullable|string',
            'photo' => 'nullable',
            'geolocation' => 'required|exists:loc_cities,id',

            'weight' => 'nullable|numeric',
            'length' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'shape' => 'nullable|string',

            'skin_color' => 'nullable|string',
            'skin_texture' => 'nullable|string',
            'flesh_color' => 'nullable|string',
            'flesh_texture' => 'nullable|string',
            'flesh_flavor' => 'nullable|string',
            'aroma' => 'nullable|string',

            'root_flesh_color' => 'nullable|string',
            'root_cortex_color' => 'nullable|string',
            'root_skin_color' => 'nullable|string',
            'root_shape' => 'nullable|string',

            'tuber_flesh_color' => 'nullable|string',
            'tuber_cortex_color' => 'nullable|string',
            'tuber_skin_color' => 'nullable|string',
            'tuber_shape' => 'nullable|string',

            'regulations' => 'nullable|array',
            'regulations.*.regulatory_body' => 'nullable|string',
            'regulations.*.registration_no' => 'nullable|string',
            'regulations.*.registration_date' => 'nullable|date',

            'stress_resilience' => 'nullable|array',
            'stress_resilience.*.type' => 'nullable|string',
            'stress_resilience.*.stress' => 'nullable|string',
            'stress_resilience.*.reaction' => 'nullable|string',

            'approved_at' => 'nullable|date',
        ];
    }
}
