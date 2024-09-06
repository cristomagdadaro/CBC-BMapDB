import BaseClass from "../../domain/base/BaseClass";
import ICountry from "../../interface/location/ICountry";

export default class DtoCountry extends BaseClass implements ICountry {
    id: number = null;
    country: string = null;
    iso_code: string = null;

    constructor(dto: ICountry) {
        super();
        this.table = 'countries';
        this.id = dto.id;
        this.country = dto.country;
        this.iso_code = dto.iso_code;
    }
}
