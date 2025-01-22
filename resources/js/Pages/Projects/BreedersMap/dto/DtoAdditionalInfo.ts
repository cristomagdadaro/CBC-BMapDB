import BaseClass from "@/Modules/core/domain/base/BaseClass";
import IAdditionalInfo from "@/Pages/Projects/BreedersMap/interface/IAdditionalInfo";

export default class DtoAdditionalInfo extends BaseClass implements IAdditionalInfo {
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

    constructor(additionalInfo: IAdditionalInfo) {
        super();
        this.id = additionalInfo.id;
        this.commodity_id = additionalInfo.commodity_id;

        this.nsic_registration = additionalInfo.nsic_registration;
        this.nsic_registration_number = additionalInfo.nsic_registration_number;
        this.nsic_year_approved = additionalInfo.nsic_year_approved;

        this.pvp_certificate_number = additionalInfo.pvp_certificate_number;
        this.pvp_registration_year = additionalInfo.pvp_registration_year;

        this.ncbp_project_type = additionalInfo.ncbp_project_type;
        this.ncbp_status = additionalInfo.ncbp_status;
        this.ncbp_supervising_ibc = additionalInfo.ncbp_supervising_ibc;
        this.ncbp_project_leaders = additionalInfo.ncbp_project_leaders;
        this.ncbp_date_approval = additionalInfo.ncbp_date_approval;
        this.ncbp_date_completion = additionalInfo.ncbp_date_completion;

        this.ao8_transformation_event = additionalInfo.ao8_transformation_event;
        this.ao8_technology_developer = additionalInfo.ao8_technology_developer;
        this.ao8_direct_use_status = additionalInfo.ao8_direct_use_status;
        this.ao8_direct_use_date_applied = additionalInfo.ao8_direct_use_date_applied;
        this.ao8_direct_use_date_approved = additionalInfo.ao8_direct_use_date_approved;
        this.ao8_field_trial_status = additionalInfo.ao8_field_trial_status;
        this.ao8_field_trial_date_applied = additionalInfo.ao8_field_trial_date_applied;
        this.ao8_field_trial_date_approved = additionalInfo.ao8_field_trial_date_approved;
        this.ao8_propagation_status = additionalInfo.ao8_propagation_status;
        this.ao8_propagation_date_applied = additionalInfo.ao8_propagation_date_applied;
        this.ao8_propagation_date_approved = additionalInfo.ao8_propagation_date_approved;

        this.jdc_2016_transformation_event = additionalInfo.jdc_2016_transformation_event;
        this.jdc_2016_technology_developer = additionalInfo.jdc_2016_technology_developer;
        this.jdc_2016_direct_use_status = additionalInfo.jdc_2016_direct_use_status;
        this.jdc_2016_direct_use_date_applied = additionalInfo.jdc_2016_direct_use_date_applied;
        this.jdc_2016_direct_use_date_approved = additionalInfo.jdc_2016_direct_use_date_approved;
        this.jdc_2016_ffp_status = additionalInfo.jdc_2016_ffp_status;
        this.jdc_2016_ffp_date_applied = additionalInfo.jdc_2016_ffp_date_applied;
        this.jdc_2016_ffp_date_approved = additionalInfo.jdc_2016_ffp_date_approved;
        this.jdc_2016_field_trial_status = additionalInfo.jdc_2016_field_trial_status;
        this.jdc_2016_field_trial_date_applied = additionalInfo.jdc_2016_field_trial_date_applied;
        this.jdc_2016_field_trial_date_approved = additionalInfo.jdc_2016_field_trial_date_approved;
        this.jdc_2016_propagation_status = additionalInfo.jdc_2016_propagation_status;
        this.jdc_2016_propagation_date_applied = additionalInfo.jdc_2016_propagation_date_applied;
        this.jdc_2016_propagation_date_approved = additionalInfo.jdc_2016_propagation_date_approved;
        this.jdc_2016_renewal_propagation_status = additionalInfo.jdc_2016_renewal_propagation_status;
        this.jdc_2016_renewal_propagation_date_applied = additionalInfo.jdc_2016_renewal_propagation_date_applied;
        this.jdc_2016_renewal_propagation_date_approved = additionalInfo.jdc_2016_renewal_propagation_date_approved;
        this.jdc_2016_deregulation_status = additionalInfo.jdc_2016_deregulation_status;
        this.jdc_2016_deregulation_date_applied = additionalInfo.jdc_2016_deregulation_date_applied;
        this.jdc_2016_deregulation_date_approved = additionalInfo.jdc_2016_deregulation_date_approved;

        this.jdc_2021_transformation_event = additionalInfo.jdc_2021_transformation_event;
        this.jdc_2021_technology_developer = additionalInfo.jdc_2021_technology_developer;
        this.jdc_2021_direct_use_status = additionalInfo.jdc_2021_direct_use_status;
        this.jdc_2021_direct_use_date_applied = additionalInfo.jdc_2021_direct_use_date_applied;
        this.jdc_2021_direct_use_date_approved = additionalInfo.jdc_2021_direct_use_date_approved;
        this.jdc_2021_field_trial_status = additionalInfo.jdc_2021_field_trial_status;
        this.jdc_2021_field_trial_date_applied = additionalInfo.jdc_2021_field_trial_date_applied;
        this.jdc_2021_field_trial_date_approved = additionalInfo.jdc_2021_field_trial_date_approved;
        this.jdc_2021_propagation_status = additionalInfo.jdc_2021_propagation_status;
        this.jdc_2021_propagation_date_applied = additionalInfo.jdc_2021_propagation_date_applied;
        this.jdc_2021_propagation_date_approved = additionalInfo.jdc_2021_propagation_date_approved;
    }
}
