import ICommodity from "../interface/ICommodity";
import IBreeder from "../interface/IBreeder";
import BaseClass from "../../../../Modules/core/domain/base/BaseClass";
import DtoCity from "../../../../Modules/core/dto/location/DtoCity";
import DtoBreeder from "./DtoBreeder";
import IUser from "@/Modules/core/interface/auth/IUser";
import DtoUser from "@/Modules/core/dto/DtoUser";
// @ts-ignore
export default class DtoCommodity extends BaseClass implements ICommodity {
    id: number;
    user_id: number;
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
    status: string;
    location: DtoCity;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    breeder: IBreeder = null;
    user: IUser = null;

    constructor(commodity: ICommodity) {
        super();
        this.id = commodity.id;
        this.user_id = commodity.user_id;
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
        this.status = commodity.status;
        this.created_at = commodity.created_at;
        this.updated_at = commodity.updated_at;
        this.deleted_at = commodity.deleted_at;

        if (commodity.breeder)
            this.breeder = new DtoBreeder(commodity.breeder);

        if (commodity.location)
            this.location = new DtoCity(commodity.location);

        if (commodity.user)
            this.user = new DtoUser(commodity.user);
    }

    get coordinates() {
        return this.location;
    }
}
