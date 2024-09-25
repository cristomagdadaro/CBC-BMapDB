import BaseClass from "../../domain/base/BaseClass";
import ICity from "../../interface/location/ICity";
import DtoProvince from "./DtoProvince";
import DtoRegion from "./DtoRegion";

export default class DtoCity extends BaseClass implements ICity {
    id: number = null;
    cityDesc: string = null;
    provDesc: DtoProvince = null;
    regDesc: DtoRegion = null;
    latitude: string = null;
    longitude: string = null;

    constructor(dto: ICity) {
        super();
        this.table = 'cities';
        this.id = dto.id;
        this.cityDesc = dto.cityDesc;
        this.latitude = dto.latitude;
        this.longitude = dto.longitude

        if (dto.provDesc)
            // @ts-ignore
            this.provDesc = new DtoProvince(dto);

        if (dto.regDesc)
            // @ts-ignore
            this.regDesc = new DtoRegion(dto);
    }

    get getFullAddress() {
        return `${this.cityDesc}, ${this.provDesc.province}, ${this.regDesc.region}`;
    }

    get city() {
        return this.cityDesc;
    }

    get province() {
        return this.provDesc.province;
    }

    get region() {
        return this.regDesc.region;
    }

    get LatLng() {
        return {
            lat: parseFloat(this.latitude),
            lng: parseFloat(this.longitude)
        }
    }
}
