import IExpert from "./IExpert";

export default interface IProduct {
    id: number;
    user_id: number;
    twg_expert_id: number;
    name: string;
    brand: string;
    purpose: string;
    cost: number;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    expert: IExpert;
}
