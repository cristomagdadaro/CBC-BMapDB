<?php

namespace App\Http\Requests;

use App\Enums\Permission;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCommoditiesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasPermissionTo(Permission::UPDATE_COMMODITY->value) || auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exist:users,id',
            'name' => 'required|string|max:255',
            'breeder_id' => 'required|integer|exists:breeders,id',
            'scientific_name' => 'required|string|max:255',
            'variety' => 'required|string|max:255',
            'accession' => 'required|string|max:255',
            'germplasm' => 'required|string|max:255',
            'population' => 'required|numeric',
            'maturity_period' => 'required|string',
            'yield' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable',
            'status' => 'required|string',
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

            'nsic_registration' => 'nullable|string',
            'nsic_registration_number' => 'nullable|string',
            'nsic_year_approved' => 'nullable|integer',

            'pvp_certificate_number' => 'nullable|string',
            'pvp_registration_year' => 'nullable|integer',

            'ncbp_project_type' => 'nullable|string',
            'ncbp_status' => 'nullable|string',
            'ncbp_supervising_ibc' => 'nullable|string',
            'ncbp_project_leaders' => 'nullable|string',
            'ncbp_date_approval' => 'nullable|date',
            'ncbp_date_completion' => 'nullable|date',

            'ao8_transformation_event' => 'nullable|string',
            'ao8_technology_developer' => 'nullable|string',
            'ao8_direct_use_status' => 'nullable|string',
            'ao8_direct_use_date_applied' => 'nullable|date',
            'ao8_direct_use_date_approved' => 'nullable|date',
            'ao8_field_trial_status' => 'nullable|string',
            'ao8_field_trial_date_applied' => 'nullable|date',
            'ao8_field_trial_date_approved' => 'nullable|date',
            'ao8_propagation_status' => 'nullable|string',
            'ao8_propagation_date_applied' => 'nullable|date',
            'ao8_propagation_date_approved' => 'nullable|date',

            'jdc_2016_transformation_event' => 'nullable|string',
            'jdc_2016_technology_developer' => 'nullable|string',
            'jdc_2016_direct_use_status' => 'nullable|string',
            'jdc_2016_direct_use_date_applied' => 'nullable|date',
            'jdc_2016_direct_use_date_approved' => 'nullable|date',
            'jdc_2016_ffp_status' => 'nullable|string',
            'jdc_2016_ffp_date_applied' => 'nullable|date',
            'jdc_2016_ffp_date_approved' => 'nullable|date',
            'jdc_2016_field_trial_status' => 'nullable|string',
            'jdc_2016_field_trial_date_applied' => 'nullable|date',
            'jdc_2016_field_trial_date_approved' => 'nullable|date',
            'jdc_2016_propagation_status' => 'nullable|string',
            'jdc_2016_propagation_date_applied' => 'nullable|date',
            'jdc_2016_propagation_date_approved' => 'nullable|date',
            'jdc_2016_renewal_propagation_status' => 'nullable|string',
            'jdc_2016_renewal_propagation_date_applied' => 'nullable|date',
            'jdc_2016_renewal_propagation_date_approved' => 'nullable|date',
            'jdc_2016_deregulation_status' => 'nullable|string',
            'jdc_2016_deregulation_date_applied' => 'nullable|date',
            'jdc_2016_deregulation_date_approved' => 'nullable|date',

            'jdc_2021_transformation_event' => 'nullable|string',
            'jdc_2021_technology_developer' => 'nullable|string',
            'jdc_2021_direct_use_status' => 'nullable|string',
            'jdc_2021_direct_use_date_applied' => 'nullable|date',
            'jdc_2021_direct_use_date_approved' => 'nullable|date',
            'jdc_2021_field_trial_status' => 'nullable|string',
            'jdc_2021_field_trial_date_applied' => 'nullable|date',
            'jdc_2021_field_trial_date_approved' => 'nullable|date',
            'jdc_2021_propagation_status' => 'nullable|string',
            'jdc_2021_propagation_date_applied' => 'nullable|date',
            'jdc_2021_propagation_date_approved' => 'nullable|date',
        ];
    }
}
