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
            this.provDesc = new DtoProvince(dto.provDesc);

        if (dto.regDesc)
            this.regDesc = new DtoRegion(dto.regDesc);
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
