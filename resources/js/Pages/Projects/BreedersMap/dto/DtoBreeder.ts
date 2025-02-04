import BaseClass from "../../../../Modules/core/domain/base/BaseClass";
import IBreeder from "../interface/IBreeder";
import DtoInstitute from "../../../../Modules/core/dto/DtoInstitute";
import DtoCity from "../../../../Modules/core/dto/location/DtoCity";
import DtoCommodity from "./DtoCommodity";

export default class DtoBreeder extends BaseClass implements IBreeder {
    id?: number;
    user_id: number;
    fname: string;
    lname: string;
    mname: string;
    suffix: string;
    affiliated: DtoInstitute;
    mobile_no: string;
    email: string;
    updated_at: string;
    created_at: string;
    deleted_at: string;
    location: DtoCity;
    commodities: DtoCommodity[];
    commodities_count?: number;

    constructor(breeder: IBreeder) {
        super();
        this.table = 'breeders';

        this.id = breeder?.id;
        this.user_id = breeder?.user_id;
        this.fname = breeder?.fname;
        this.mname = breeder?.mname;
        this.lname = breeder?.lname;
        this.suffix = breeder?.suffix;
        this.mobile_no = breeder?.mobile_no;
        this.email = breeder?.email;
        this.updated_at = breeder?.updated_at;
        this.created_at = breeder?.created_at;
        this.deleted_at = breeder?.deleted_at;

        this.commodities_count = breeder?.commodities_count;

        if (breeder?.affiliated)
            this.affiliated = new DtoInstitute(breeder.affiliated);

        if (breeder?.location)
            this.location = new DtoCity(breeder.location);

        if (breeder?.commodities)
            this.commodities = breeder.commodities.map(commodity => new DtoCommodity(commodity));
    }

    get getAffiliation() {
        return this.affiliated.getInstituteName;
    }

    get getEmail() {
        return this.email;
    }

    get getMobileNo() {
        return this.mobile_no;
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

    get commoditiesCount() {
        return this.commodities_count;
    }
}
