import BaseClass from "../../../../Modules/core/domain/base/BaseClass";
import IBreeder from "../interface/IBreeder";

export default class DtoBreeder extends BaseClass implements IBreeder {
    id: number;
    user_id: number;
    name: string;
    agency: string;
    phone: string;
    email: string;
    address: string;
    updated_at: string;
    created_at: string;
    deleted_at: string;

    constructor(breeder: IBreeder) {
        super();
        this.id = breeder.id;
        this.user_id = breeder.user_id;
        this.name = breeder.name;
        this.agency = breeder.agency;
        this.phone = breeder.phone;
        this.email = breeder.email;
        this.address = breeder.address;
        this.updated_at = breeder.updated_at;
        this.created_at = breeder.created_at;
        this.deleted_at = breeder.deleted_at;
    }
}