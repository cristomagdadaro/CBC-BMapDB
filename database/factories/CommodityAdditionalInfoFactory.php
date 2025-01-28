<?php

namespace Database\Factories;

use App\Models\Commodity;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
 */
class CommodityAdditionalInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'commodity_id' => Commodity::inRandomOrder()->first()->id,
            'nsic_registration' => $this->faker->word,  // Random word or appropriate data
            'nsic_registration_number' => $this->faker->word,  // Random string or appropriate data
            'nsic_year_approved' => $this->faker->year,  // Random year for NSIC approval

            'pvp_certificate_number' => $this->faker->word,  // Random string for PVP Certificate
            'pvp_registration_year' => $this->faker->year,  // Random year for PVP Registration

            'ncbp_project_type' => $this->faker->word,  // Random string for NCBP project type
            'ncbp_status' => $this->faker->word,  // Random string for NCBP status
            'ncbp_supervising_ibc' => $this->faker->word,  // Random string for Supervising IBC
            'ncbp_project_leaders' => $this->faker->name,  // Random name for NCBP project leaders
            'ncbp_date_approval' => $this->faker->date(),  // Random date for NCBP approval
            'ncbp_date_completion' => $this->faker->date(),  // Random date for NCBP completion

            'ao8_transformation_event' => $this->faker->word,  // Random string for AO8 transformation event
            'ao8_technology_developer' => $this->faker->word,  // Random string for AO8 technology developer
            'ao8_direct_use_status' => $this->faker->word,  // Random string for AO8 direct use status
            'ao8_direct_use_date_applied' => $this->faker->date(),  // Random date for AO8 direct use applied
            'ao8_direct_use_date_approved' => $this->faker->date(),  // Random date for AO8 direct use approval
            'ao8_field_trial_status' => $this->faker->word,  // Random string for AO8 field trial status
            'ao8_field_trial_date_applied' => $this->faker->date(),  // Random date for AO8 field trial applied
            'ao8_field_trial_date_approved' => $this->faker->date(),  // Random date for AO8 field trial approval
            'ao8_propagation_status' => $this->faker->word,  // Random string for AO8 propagation status
            'ao8_propagation_date_applied' => $this->faker->date(),  // Random date for AO8 propagation applied
            'ao8_propagation_date_approved' => $this->faker->date(),  // Random date for AO8 propagation approval

            'jdc_2016_transformation_event' => $this->faker->word,  // Random string for JDC 2016 transformation event
            'jdc_2016_technology_developer' => $this->faker->word,  // Random string for JDC 2016 technology developer
            'jdc_2016_direct_use_status' => $this->faker->word,  // Random string for JDC 2016 direct use status
            'jdc_2016_direct_use_date_applied' => $this->faker->date(),  // Random date for JDC 2016 direct use applied
            'jdc_2016_direct_use_date_approved' => $this->faker->date(),  // Random date for JDC 2016 direct use approval
            'jdc_2016_ffp_status' => $this->faker->word,  // Random string for JDC 2016 FFP status
            'jdc_2016_ffp_date_applied' => $this->faker->date(),  // Random date for JDC 2016 FFP applied
            'jdc_2016_ffp_date_approved' => $this->faker->date(),  // Random date for JDC 2016 FFP approval
            'jdc_2016_field_trial_status' => $this->faker->word,  // Random string for JDC 2016 field trial status
            'jdc_2016_field_trial_date_applied' => $this->faker->date(),  // Random date for JDC 2016 field trial applied
            'jdc_2016_field_trial_date_approved' => $this->faker->date(),  // Random date for JDC 2016 field trial approval
            'jdc_2016_propagation_status' => $this->faker->word,  // Random string for JDC 2016 propagation status
            'jdc_2016_propagation_date_applied' => $this->faker->date(),  // Random date for JDC 2016 propagation applied
            'jdc_2016_propagation_date_approved' => $this->faker->date(),  // Random date for JDC 2016 propagation approval
            'jdc_2016_renewal_propagation_status' => $this->faker->word,  // Random string for JDC 2016 renewal propagation status
            'jdc_2016_renewal_propagation_date_applied' => $this->faker->date(),  // Random date for JDC 2016 renewal propagation applied
            'jdc_2016_renewal_propagation_date_approved' => $this->faker->date(),  // Random date for JDC 2016 renewal propagation approval
            'jdc_2016_deregulation_status' => $this->faker->word,  // Random string for JDC 2016 deregulation status
            'jdc_2016_deregulation_date_applied' => $this->faker->date(),  // Random date for JDC 2016 deregulation applied
            'jdc_2016_deregulation_date_approved' => $this->faker->date(),  // Random date for JDC 2016 deregulation approval

            'jdc_2021_transformation_event' => $this->faker->word,  // Random string for JDC 2021 transformation event
            'jdc_2021_technology_developer' => $this->faker->word,  // Random string for JDC 2021 technology developer
            'jdc_2021_direct_use_status' => $this->faker->word,  // Random string for JDC 2021 direct use status
            'jdc_2021_direct_use_date_applied' => $this->faker->date(),  // Random date for JDC 2021 direct use applied
            'jdc_2021_direct_use_date_approved' => $this->faker->date(),  // Random date for JDC 2021 direct use approval
            'jdc_2021_field_trial_status' => $this->faker->word,  // Random string for JDC 2021 field trial status
            'jdc_2021_field_trial_date_applied' => $this->faker->date(),  // Random date for JDC 2021 field trial applied
            'jdc_2021_field_trial_date_approved' => $this->faker->date(),  // Random date for JDC 2021 field trial approval
            'jdc_2021_propagation_status' => $this->faker->word,  // Random string for JDC 2021 propagation status
            'jdc_2021_propagation_date_applied' => $this->faker->date(),  // Random date for JDC 2021 propagation applied
            'jdc_2021_propagation_date_approved' => $this->faker->date(),  // Random date for JDC 2021 propagation approval
        ];

    }
}
