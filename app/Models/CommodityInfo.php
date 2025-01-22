<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommodityInfo extends BaseModel
{
    use HasFactory;

    protected $table = 'commodity_info';

    protected $fillable = [
        'commodity_id',

        // NSIC Registration
        'nsic_registration',
        'nsic_registration_number',
        'nsic_year_approved',

        // Plant Variety Protection
        'pvp_certificate_number',
        'pvp_registration_year',

        // GM Regulatory Approval: NCBP
        'ncbp_project_type',
        'ncbp_status',
        'ncbp_supervising_ibc',
        'ncbp_project_leaders',
        'ncbp_date_approval',
        'ncbp_date_completion',

        // GM Regulatory Approval: AO8
        'ao8_transformation_event',
        'ao8_technology_developer',
        'ao8_direct_use_status',
        'ao8_direct_use_date_applied',
        'ao8_direct_use_date_approved',
        'ao8_field_trial_status',
        'ao8_field_trial_date_applied',
        'ao8_field_trial_date_approved',
        'ao8_propagation_status',
        'ao8_propagation_date_applied',
        'ao8_propagation_date_approved',

        // GM Regulatory Approval: JDC 2016
        'jdc_2016_transformation_event',
        'jdc_2016_technology_developer',
        'jdc_2016_direct_use_status',
        'jdc_2016_direct_use_date_applied',
        'jdc_2016_direct_use_date_approved',
        'jdc_2016_ffp_status',
        'jdc_2016_ffp_date_applied',
        'jdc_2016_ffp_date_approved',
        'jdc_2016_field_trial_status',
        'jdc_2016_field_trial_date_applied',
        'jdc_2016_field_trial_date_approved',
        'jdc_2016_propagation_status',
        'jdc_2016_propagation_date_applied',
        'jdc_2016_propagation_date_approved',
        'jdc_2016_renewal_propagation_status',
        'jdc_2016_renewal_propagation_date_applied',
        'jdc_2016_renewal_propagation_date_approved',
        'jdc_2016_deregulation_status',
        'jdc_2016_deregulation_date_applied',
        'jdc_2016_deregulation_date_approved',

        // GM Regulatory Approval: JDC 2021
        'jdc_2021_transformation_event',
        'jdc_2021_technology_developer',
        'jdc_2021_direct_use_status',
        'jdc_2021_direct_use_date_applied',
        'jdc_2021_direct_use_date_approved',
        'jdc_2021_field_trial_status',
        'jdc_2021_field_trial_date_applied',
        'jdc_2021_field_trial_date_approved',
        'jdc_2021_propagation_status',
        'jdc_2021_propagation_date_applied',
        'jdc_2021_propagation_date_approved',
    ];

    protected array $searchable = [
        'nsic_registration',
        'nsic_registration_number',
        'nsic_year_approved',
        'pvp_certificate_number',
        'pvp_registration_year',
        'ncbp_project_type',
        'ncbp_status',
        'ncbp_supervising_ibc',
        'ncbp_project_leaders',
        'ncbp_date_approval',
        'ncbp_date_completion',
        'ao8_transformation_event',
        'ao8_technology_developer',
        'ao8_direct_use_status',
        'ao8_direct_use_date_applied',
        'ao8_direct_use_date_approved',
        'ao8_field_trial_status',
        'ao8_field_trial_date_applied',
        'ao8_field_trial_date_approved',
        'ao8_propagation_status',
        'ao8_propagation_date_applied',
        'ao8_propagation_date_approved',
        'jdc_2016_transformation_event',
        'jdc_2016_technology_developer',
        'jdc_2016_direct_use_status',
        'jdc_2016_direct_use_date_applied',
        'jdc_2016_direct_use_date_approved',
        'jdc_2016_ffp_status',
        'jdc_2016_ffp_date_applied',
        'jdc_2016_ffp_date_approved',
        'jdc_2016_field_trial_status',
        'jdc_2016_field_trial_date_applied',
        'jdc_2016_field_trial_date_approved',
        'jdc_2016_propagation_status',
        'jdc_2016_propagation_date_applied',
        'jdc_2016_propagation_date_approved',
        'jdc_2016_renewal_propagation_status',
        'jdc_2016_renewal_propagation_date_applied',
        'jdc_2016_renewal_propagation_date_approved',
        'jdc_2016_deregulation_status',
        'jdc_2016_deregulation_date_applied',
        'jdc_2016_deregulation_date_approved',
        'jdc_2021_transformation_event',
        'jdc_2021_technology_developer',
        'jdc_2021_direct_use_status',
        'jdc_2021_direct_use_date_applied',
        'jdc_2021_direct_use_date_approved',
        'jdc_2021_field_trial_status',
        'jdc_2021_field_trial_date_applied',
        'jdc_2021_field_trial_date_approved',
        'jdc_2021_propagation_status',
        'jdc_2021_propagation_date_applied',
        'jdc_2021_propagation_date_approved',
    ];

    public function commodity()
    {
        return $this->belongsTo(Commodity::class);
    }

}
