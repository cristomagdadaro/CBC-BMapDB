import BaseClass from "../../domain/base/BaseClass";
import IRegion from "../../interface/location/IRegion";
import ICountry from "../../interface/location/ICountry";
import DtoCountry from "./DtoCountry";

export default class DtoRegion extends BaseClass implements IRegion {
    id: number = null;
    regDesc: string = null;
    regDescLong: string = null;
    country_id: ICountry = null;

    constructor(dto: IRegion) {
        super();
        this.table = 'regions';
        this.id = dto.id;
        this.regDesc = dto.regDesc;
        this.regDescLong = dto.regDescLong;
        if (dto.country_id)
            this.country_id = new DtoCountry(dto.country_id);
    }

    get region() {
        return this.regDesc;
    }
}
