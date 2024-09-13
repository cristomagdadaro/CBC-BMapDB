import BaseClass from "../../../../Modules/core/domain/base/BaseClass";
import IBreeder from "../interface/IBreeder";
import DtoInstitute from "../../../../Modules/core/dto/DtoInstitute";

export default class DtoBreeder extends BaseClass implements IBreeder {
    id: number;
    user_id: number;
    name: string;
    affiliated: DtoInstitute;
    phone: string;
    email: string;
    updated_at: string;
    created_at: string;
    deleted_at: string;

    constructor(breeder: IBreeder) {
        super();
        this.id = breeder.id;
        this.user_id = breeder.user_id;
        this.name = breeder.name;
        this.phone = breeder.phone;
        this.email = breeder.email;
        this.updated_at = breeder.updated_at;
        this.created_at = breeder.created_at;
        this.deleted_at = breeder.deleted_at;

        if (breeder.affiliated)
            this.affiliated = new DtoInstitute(breeder.affiliated);

        console.log(this);
    }

    get city() {
        return this.affiliated.city;
    }

    get province() {
        return this.affiliated.province;
    }

    get region() {
        return this.affiliated.region;
    }
}
