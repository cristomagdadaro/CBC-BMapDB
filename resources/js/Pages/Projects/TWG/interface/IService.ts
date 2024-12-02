import IExpert from "./IExpert";

export default interface IService {
    id: number;
    user_id: number;
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
}
