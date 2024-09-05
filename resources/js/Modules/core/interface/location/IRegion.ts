import ICountry from "./ICountry";

export default interface IRegion {
    id: number;
    psgcCode: string;
    regDesc: string;
    regCode: string;
    country_id: ICountry;
}
