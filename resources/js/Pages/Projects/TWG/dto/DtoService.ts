import IService from "../interface/IService";
import BaseClass from "../../../../Modules/core/domain/base/BaseClass";
import DtoInstitute from "@/Modules/core/dto/DtoInstitute";
import IInstitute from "@/Modules/core/interface/auth/IInstitute";
import IExpert from "@/Pages/Projects/TWG/interface/IExpert";
import DtoExpert from "@/Pages/Projects/TWG/dto/DtoExpert";

export default class DtoService extends BaseClass implements IService {
    id: number;
    user_id: number;
    type: string;
    purpose: string;
    direct_beneficiaries: string;
    indirect_beneficiaries: string;
    cost: number;
    institution: number;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    officer_in_charge: IExpert;
    affiliated: IInstitute;

    constructor(service: IService) {
        super();
        this.table = 'services';
        this.id = service.id;
        this.type = service.type;
        this.purpose = service.purpose;
        this.direct_beneficiaries = service.direct_beneficiaries;
        this.indirect_beneficiaries = service.indirect_beneficiaries;
        this.cost = service.cost;
        this.created_at = service.created_at;
        this.updated_at = service.updated_at;
        this.deleted_at = service.deleted_at;
        this.institution = service.institution;

        this.officer_in_charge = new DtoExpert(service?.officer_in_charge);
        this.affiliated = new DtoInstitute(service?.affiliated);
    }
}
