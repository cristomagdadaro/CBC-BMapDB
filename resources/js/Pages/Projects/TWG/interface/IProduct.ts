import IInstitute from "@/Modules/core/interface/auth/IInstitute";

export default interface IProduct {
    id: number;
    user_id: number;
    name: string;
    brand: string;
    purpose: string;
    cost: number;
    institution: number;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    affiliated: IInstitute;
}
