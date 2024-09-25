import IRegion from "./IRegion";
import IProvince from "./IProvince";

export default interface ICity {
    id: number;
    latitude: string;
    longitude: string;
    cityDesc: string
    provDesc: IProvince;
    regDesc: IRegion;
}
