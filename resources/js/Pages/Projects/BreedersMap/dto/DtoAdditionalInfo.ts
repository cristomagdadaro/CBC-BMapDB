import BaseClass from "@/Modules/core/domain/base/BaseClass";
import ICommodityAdditionalInfo from "@/Pages/Projects/BreedersMap/interface/IAdditionalInfo";

export default class DtoAdditionalInfo extends BaseClass implements ICommodityAdditionalInfo {
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

    constructor(additionalInfo: ICommodityAdditionalInfo) {
        super();
        this.commodity_id = additionalInfo.commodity_id;

        this.nsicRegistration = additionalInfo.nsicRegistration;
        this.nsicRegistrationNumber = additionalInfo.nsicRegistrationNumber;
        this.nsicYearApproved = additionalInfo.nsicYearApproved;

        this.pvpCertificateNumber = additionalInfo.pvpCertificateNumber;
        this.pvpRegistrationYear = additionalInfo.pvpRegistrationYear;

        this.ncbpProjectType = additionalInfo.ncbpProjectType;
        this.ncbpStatus = additionalInfo.ncbpStatus;
        this.ncbpSupervisingIbc = additionalInfo.ncbpSupervisingIbc;
        this.ncbpProjectLeaders = additionalInfo.ncbpProjectLeaders;
        this.ncbpDateApproval = additionalInfo.ncbpDateApproval;
        this.ncbpDateCompletion = additionalInfo.ncbpDateCompletion;

        this.ao8TransformationEvent = additionalInfo.ao8TransformationEvent;
        this.ao8TechnologyDeveloper = additionalInfo.ao8TechnologyDeveloper;
        this.ao8DirectUseStatus = additionalInfo.ao8DirectUseStatus;
        this.ao8DirectUseDateApplied = additionalInfo.ao8DirectUseDateApplied;
        this.ao8DirectUseDateApproved = additionalInfo.ao8DirectUseDateApproved;
        this.ao8FieldTrialStatus = additionalInfo.ao8FieldTrialStatus;
        this.ao8FieldTrialDateApplied = additionalInfo.ao8FieldTrialDateApplied;
        this.ao8FieldTrialDateApproved = additionalInfo.ao8FieldTrialDateApproved;
        this.ao8PropagationStatus = additionalInfo.ao8PropagationStatus;
        this.ao8PropagationDateApplied = additionalInfo.ao8PropagationDateApplied;
        this.ao8PropagationDateApproved = additionalInfo.ao8PropagationDateApproved;

        this.jdc2016TransformationEvent = additionalInfo.jdc2016TransformationEvent;
        this.jdc2016TechnologyDeveloper = additionalInfo.jdc2016TechnologyDeveloper;
        this.jdc2016DirectUseStatus = additionalInfo.jdc2016DirectUseStatus;
        this.jdc2016DirectUseDateApplied = additionalInfo.jdc2016DirectUseDateApplied;
        this.jdc2016DirectUseDateApproved = additionalInfo.jdc2016DirectUseDateApproved;
        this.jdc2016FfpStatus = additionalInfo.jdc2016FfpStatus;
        this.jdc2016FfpDateApplied = additionalInfo.jdc2016FfpDateApplied;
        this.jdc2016FfpDateApproved = additionalInfo.jdc2016FfpDateApproved;
        this.jdc2016FieldTrialStatus = additionalInfo.jdc2016FieldTrialStatus;
        this.jdc2016FieldTrialDateApplied = additionalInfo.jdc2016FieldTrialDateApplied;
        this.jdc2016FieldTrialDateApproved = additionalInfo.jdc2016FieldTrialDateApproved;
        this.jdc2016PropagationStatus = additionalInfo.jdc2016PropagationStatus;
        this.jdc2016PropagationDateApplied = additionalInfo.jdc2016PropagationDateApplied;
        this.jdc2016PropagationDateApproved = additionalInfo.jdc2016PropagationDateApproved;
        this.jdc2016RenewalPropagationStatus = additionalInfo.jdc2016RenewalPropagationStatus;
        this.jdc2016RenewalPropagationDateApplied = additionalInfo.jdc2016RenewalPropagationDateApplied;
        this.jdc2016RenewalPropagationDateApproved = additionalInfo.jdc2016RenewalPropagationDateApproved;
        this.jdc2016DeregulationStatus = additionalInfo.jdc2016DeregulationStatus;
        this.jdc2016DeregulationDateApplied = additionalInfo.jdc2016DeregulationDateApplied;
        this.jdc2016DeregulationDateApproved = additionalInfo.jdc2016DeregulationDateApproved;

        this.jdc2021TransformationEvent = additionalInfo.jdc2021TransformationEvent;
        this.jdc2021TechnologyDeveloper = additionalInfo.jdc2021TechnologyDeveloper;
        this.jdc2021DirectUseStatus = additionalInfo.jdc2021DirectUseStatus;
        this.jdc2021DirectUseDateApplied = additionalInfo.jdc2021DirectUseDateApplied;
        this.jdc2021DirectUseDateApproved = additionalInfo.jdc2021DirectUseDateApproved;
        this.jdc2021FieldTrialStatus = additionalInfo.jdc2021FieldTrialStatus;
        this.jdc2021FieldTrialDateApplied = additionalInfo.jdc2021FieldTrialDateApplied;
        this.jdc2021FieldTrialDateApproved = additionalInfo.jdc2021FieldTrialDateApproved;
        this.jdc2021PropagationStatus = additionalInfo.jdc2021PropagationStatus;
        this.jdc2021PropagationDateApplied = additionalInfo.jdc2021PropagationDateApplied;
        this.jdc2021PropagationDateApproved = additionalInfo.jdc2021PropagationDateApproved;
    }
}
