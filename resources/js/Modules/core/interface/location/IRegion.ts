import ICountry from "./ICountry";

export default interface IRegion {
    id: number;
    regDesc: string;
    regDescLong: string;
    country_id: ICountry;
}
