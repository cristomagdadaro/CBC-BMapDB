import IBreeder from "./IBreeder";
import ICity from "../../../../Modules/core/interface/location/ICity";
import IProvince from "../../../../Modules/core/interface/location/IProvince";
import IRegion from "../../../../Modules/core/interface/location/IRegion";
import ICountry from "../../../../Modules/core/interface/location/ICountry";
import IBaseClass from "../../../../Modules/core/interface/base/IBaseClass";

export default interface ICommodity  extends IBaseClass {
    id: number;
    name: string;
    breeder_id: number;
    scientific_name: string;
    variety: string;
    accession: string;
    germplasm: string;
    population: string;
    maturity_period: string;
    yield: string;
    description: string;
    city_desc: ICity;
    province: IProvince;
    region: IRegion;
    country: ICountry;
    status: string;

    breeder: IBreeder;
}
