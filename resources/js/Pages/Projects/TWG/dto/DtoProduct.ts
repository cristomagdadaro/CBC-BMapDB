import IProduct from "../interface/IProduct";
import BaseClass from "../../../../Modules/core/domain/base/BaseClass";
import IExpert from "../interface/IExpert";
import Expert from "../domain/Expert";

export default class DtoProduct extends BaseClass implements IProduct {
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

    constructor(product: IProduct) {
        super();
        this.table = 'products';
        this.id = product.id;
        this.twg_expert_id = product.twg_expert_id;
        this.name = product.name;
        this.brand = product.brand;
        this.purpose = product.purpose;
        this.cost = product.cost;
        this.created_at = product.created_at;
        this.updated_at = product.updated_at;
        this.deleted_at = product.deleted_at;

        if (product.expert)
        //@ts-ignore
        this.expert = new Expert(product.expert);
    }
}
