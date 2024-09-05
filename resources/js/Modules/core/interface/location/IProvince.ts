import IRegion from "./IRegion";

export default interface IProvince {
    id: number;
    psgcCode: string;
    provDesc: number;
    regCode: IRegion;
    provCode: string;
}
