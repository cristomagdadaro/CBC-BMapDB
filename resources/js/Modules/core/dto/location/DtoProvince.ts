import BaseClass from "../../domain/base/BaseClass";
import IProvince from "../../interface/location/IProvince";
import DtoRegion from "./DtoRegion";

export default class DtoProvince extends BaseClass implements IProvince {
    id: number = null;
    provDesc: string = null;
    regDesc: DtoRegion = null;

    constructor(dto: IProvince) {
        super();
        this.table = 'provinces';
        this.id = dto.id;
        this.provDesc = dto.provDesc;

        if (dto.regDesc)
            this.regDesc = new DtoRegion(dto.regDesc);
    }

    get province() {
        return this.provDesc;
    }

    get region() {
        return this.regDesc.region;
    }
}
