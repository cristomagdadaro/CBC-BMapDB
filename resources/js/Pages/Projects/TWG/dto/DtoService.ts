import IService from "../interface/IService";
import BaseClass from "../../../../Modules/core/domain/base/BaseClass";
import Expert from "../domain/Expert";
import IExpert from "../interface/IExpert";

export default class DtoService extends BaseClass implements IService {
    id: number;
    twg_expert_id: number;
    type: string;
    purpose: string;
    direct_beneficiaries: string;
    indirect_beneficiaries: string;
    officer_in_charge: string;
    cost: number;
    created_at: string;
    updated_at: string;
    deleted_at: string;
    expert: IExpert;

    constructor(service: IService) {
        super();
        this.id = service.id;
        this.twg_expert_id = service.twg_expert_id;
        this.type = service.type;
        this.purpose = service.purpose;
        this.direct_beneficiaries = service.direct_beneficiaries;
        this.indirect_beneficiaries = service.indirect_beneficiaries;
        this.officer_in_charge = service.officer_in_charge;
        this.cost = service.cost;
        this.created_at = service.created_at;
        this.updated_at = service.updated_at;
        this.deleted_at = service.deleted_at;

        //@ts-ignore
        this.expert = new Expert(service.expert);
    }
}
