export default interface IAdditionalInfo {
    id ?: number;
    commodity_id?: number;

    nsic_registration?: boolean;
    nsic_registration_number?: string;
    nsic_year_approved?: number;

    pvp_certificate_number?: string;
    pvp_registration_year?: number;

    ncbp_project_type?: string;
    ncbp_status?: string;
    ncbp_supervising_ibc?: string;
    ncbp_project_leaders?: string;
    ncbp_date_approval?: Date;
    ncbp_date_completion?: Date;

    ao8_transformation_event?: string;
    ao8_technology_developer?: string;
    ao8_direct_use_status?: string;
    ao8_direct_use_date_applied?: Date;
    ao8_direct_use_date_approved?: Date;
    ao8_field_trial_status?: string;
    ao8_field_trial_date_applied?: Date;
    ao8_field_trial_date_approved?: Date;
    ao8_propagation_status?: string;
    ao8_propagation_date_applied?: Date;
    ao8_propagation_date_approved?: Date;

    jdc_2016_transformation_event?: string;
    jdc_2016_technology_developer?: string;
    jdc_2016_direct_use_status?: string;
    jdc_2016_direct_use_date_applied?: Date;
    jdc_2016_direct_use_date_approved?: Date;
    jdc_2016_ffp_status?: string;
    jdc_2016_ffp_date_applied?: Date;
    jdc_2016_ffp_date_approved?: Date;
    jdc_2016_field_trial_status?: string;
    jdc_2016_field_trial_date_applied?: Date;
    jdc_2016_field_trial_date_approved?: Date;
    jdc_2016_propagation_status?: string;
    jdc_2016_propagation_date_applied?: Date;
    jdc_2016_propagation_date_approved?: Date;
    jdc_2016_renewal_propagation_status?: string;
    jdc_2016_renewal_propagation_date_applied?: Date;
    jdc_2016_renewal_propagation_date_approved?: Date;
    jdc_2016_deregulation_status?: string;
    jdc_2016_deregulation_date_applied?: Date;
    jdc_2016_deregulation_date_approved?: Date;

    jdc_2021_transformation_event?: string;
    jdc_2021_technology_developer?: string;
    jdc_2021_direct_use_status?: string;
    jdc_2021_direct_use_date_applied?: Date;
    jdc_2021_direct_use_date_approved?: Date;
    jdc_2021_field_trial_status?: string;
    jdc_2021_field_trial_date_applied?: Date;
    jdc_2021_field_trial_date_approved?: Date;
    jdc_2021_propagation_status?: string;
    jdc_2021_propagation_date_applied?: Date;
    jdc_2021_propagation_date_approved?: Date;
}
