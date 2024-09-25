import IBreeder from "./IBreeder";
import ICity from "../../../../Modules/core/interface/location/ICity";
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
    status: string;
    location: ICity;

    breeder: IBreeder;
}
