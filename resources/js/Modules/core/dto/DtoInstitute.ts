import BaseClass from "../domain/base/BaseClass";
import IInstitute from "../interface/auth/IInstitute";
import DtoCity from "./location/DtoCity";

export default class DtoInstitute extends BaseClass implements IInstitute {
    id: number;
    name: string;
    inst_type: string;
    geolocation: DtoCity;
    website: string;
    email: string;
    phone: string;

    constructor(dto: IInstitute) {
        super();
        this.table = 'institute';
        this.id = dto.id;
        this.name = dto.name;
        this.inst_type = dto.inst_type;
        this.website = dto.website;
        this.email = dto.email;
        this.phone = dto.phone;

        if (dto.geolocation)
            this.geolocation = new DtoCity(dto['city']);
    }

    get city() {
        return this.geolocation.city;
    }

    get province() {
        return this.geolocation.province;
    }

    get region() {
        return this.geolocation.region;
    }
}
