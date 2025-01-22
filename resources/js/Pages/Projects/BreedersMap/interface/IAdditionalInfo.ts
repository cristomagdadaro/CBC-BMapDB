export default interface ICommodityAdditionalInfo {
    // Foreign Key
    commodity_id: number;

    // NSIC Registration
    nsicRegistration ?: string;
    nsicRegistrationNumber ?: string;
    nsicYearApproved ?: number; // Year as number

    // Plant Variety Protection
    pvpCertificateNumber ?: string;
    pvpRegistrationYear ?: number; // Year as number

    // GM Regulatory Approval
    // NCBP
    ncbpProjectType ?: string;
    ncbpStatus ?: string;
    ncbpSupervisingIbc ?: string;
    ncbpProjectLeaders ?: string;
    ncbpDateApproval ?: string; // ISO date string
    ncbpDateCompletion ?: string; // ISO date string

    // AO8
    ao8TransformationEvent ?: string;
    ao8TechnologyDeveloper ?: string;
    ao8DirectUseStatus ?: string;
    ao8DirectUseDateApplied ?: string; // ISO date string
    ao8DirectUseDateApproved ?: string; // ISO date string
    ao8FieldTrialStatus ?: string;
    ao8FieldTrialDateApplied ?: string; // ISO date string
    ao8FieldTrialDateApproved ?: string; // ISO date string
    ao8PropagationStatus ?: string;
    ao8PropagationDateApplied ?: string; // ISO date string
    ao8PropagationDateApproved ?: string; // ISO date string

    // JDC 2016
    jdc2016TransformationEvent ?: string;
    jdc2016TechnologyDeveloper ?: string;
    jdc2016DirectUseStatus ?: string;
    jdc2016DirectUseDateApplied ?: string; // ISO date string
    jdc2016DirectUseDateApproved ?: string; // ISO date string
    jdc2016FfpStatus ?: string;
    jdc2016FfpDateApplied ?: string; // ISO date string
    jdc2016FfpDateApproved ?: string; // ISO date string
    jdc2016FieldTrialStatus ?: string;
    jdc2016FieldTrialDateApplied ?: string; // ISO date string
    jdc2016FieldTrialDateApproved ?: string; // ISO date string
    jdc2016PropagationStatus ?: string;
    jdc2016PropagationDateApplied ?: string; // ISO date string
    jdc2016PropagationDateApproved ?: string; // ISO date string
    jdc2016RenewalPropagationStatus ?: string;
    jdc2016RenewalPropagationDateApplied ?: string; // ISO date string
    jdc2016RenewalPropagationDateApproved ?: string; // ISO date string
    jdc2016DeregulationStatus ?: string;
    jdc2016DeregulationDateApplied ?: string; // ISO date string
    jdc2016DeregulationDateApproved ?: string; // ISO date string

    // JDC 2021
    jdc2021TransformationEvent ?: string;
    jdc2021TechnologyDeveloper ?: string;
    jdc2021DirectUseStatus ?: string;
    jdc2021DirectUseDateApplied ?: string; // ISO date string
    jdc2021DirectUseDateApproved ?: string; // ISO date string
    jdc2021FieldTrialStatus ?: string;
    jdc2021FieldTrialDateApplied ?: string; // ISO date string
    jdc2021FieldTrialDateApproved ?: string; // ISO date string
    jdc2021PropagationStatus ?: string;
    jdc2021PropagationDateApplied ?: string; // ISO date string
    jdc2021PropagationDateApproved ?: string; // ISO date string
}
