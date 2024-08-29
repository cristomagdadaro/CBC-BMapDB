import ICommodity from "../interface/ICommodity";
import IBreeder from "../interface/IBreeder";
import BaseClass from "../../../../Modules/core/domain/base/BaseClass";
import Breeder from "../domain/Breeder";

export default class DtoCommodity extends BaseClass implements ICommodity {
    id: number;
    breeder_id: number;
    name: string;
    scientific_name: string;
    variety: string;
    accession: string;
    germplasm: string;
    population: string;
    maturity_period: string;
    yield: string;
    description: string;
    latitude: string;
    longitude: string;
    city: string;
    province: string;
    region: string;
    country: string;
    status: string;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    breeder: IBreeder = null;

    constructor(commodity: ICommodity) {
        super();
        this.id = commodity.id;
        this.name = commodity.name;
        this.breeder_id = commodity.breeder_id;
        this.scientific_name = commodity.scientific_name;
        this.variety = commodity.variety;
        this.accession = commodity.accession;
        this.germplasm = commodity.germplasm;
        this.population = commodity.population;
        this.maturity_period = commodity.maturity_period;
        this.yield = commodity.yield;
        this.description = commodity.description;
        this.latitude = commodity.latitude;
        this.longitude = commodity.longitude;
        this.city = commodity.city;
        this.province = commodity.province;
        this.region = commodity.region;
        this.country = commodity.country;
        this.status = commodity.status;
        this.created_at = commodity.created_at;
        this.updated_at = commodity.updated_at;
        this.deleted_at = commodity.deleted_at;

        if (commodity.breeder)
            //@ts-ignore
            this.breeder = new Breeder(commodity.breeder);
    }
}
