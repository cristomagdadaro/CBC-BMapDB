import IProduct from "../interface/IProduct";
import BaseClass from "../../../../Modules/core/domain/base/BaseClass";
import IInstitute from "@/Modules/core/interface/auth/IInstitute";
import DtoInstitute from "@/Modules/core/dto/DtoInstitute";

export default class DtoProduct extends BaseClass implements IProduct {
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

    constructor(product: IProduct) {
        super();
        this.table = 'products';
        this.id = product.id;
        this.name = product.name;
        this.brand = product.brand;
        this.purpose = product.purpose;
        this.cost = product.cost;
        this.created_at = product.created_at;
        this.updated_at = product.updated_at;
        this.deleted_at = product.deleted_at;
        this.institution = product.institution;

        this.affiliated = new DtoInstitute(product?.affiliated);
    }
}
