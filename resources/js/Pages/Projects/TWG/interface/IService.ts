import IInstitute from "@/Modules/core/interface/auth/IInstitute";
import IExpert from "@/Pages/Projects/TWG/interface/IExpert";

export default interface IService {
    id: number;
    user_id: number;
    type: string;
    institution: number;
    purpose: string;
    direct_beneficiaries: string;
    indirect_beneficiaries: string;
    cost: number;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    officer_in_charge: IExpert;
    affiliated: IInstitute;
}
