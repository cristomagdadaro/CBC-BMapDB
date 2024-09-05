import IRegion from "./IRegion";
import IProvince from "./IProvince";

export default interface ICity {
    id: number;
    latitude: string;
    longitude: number;
    psgcCode: string;
    citymunDesc: string;
    citymunCode: string;
    provCode: IProvince;
    regDesc: IRegion;
}
