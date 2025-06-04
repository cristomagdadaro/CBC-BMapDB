import ICommodity from "../interface/ICommodity";
import IBreeder from "../interface/IBreeder";
import BaseClass from "../../../../Modules/core/domain/base/BaseClass";
import DtoCity from "../../../../Modules/core/dto/location/DtoCity";
import DtoBreeder from "./DtoBreeder";
import IUser from "@/Modules/core/interface/auth/IUser";
import DtoUser from "@/Modules/core/dto/DtoUser";
import ICharacteristics from "@/Pages/Projects/BreedersMap/interface/ICharacteristics";
import IAdditionalInfo from "@/Pages/Projects/BreedersMap/interface/IAdditionalInfo";
import DtoCharacteristics from "@/Pages/Projects/BreedersMap/dto/DtoCharacteristics";
import DtoAdditionalInfo from "@/Pages/Projects/BreedersMap/dto/DtoAdditionalInfo";

// @ts-ignore
export default class DtoCommodity extends BaseClass implements ICommodity {
    id: number;
    user_id: number;
    breeder_id: number;
    name: string;
    scientific_name: string;
    accession: string;
    yield: string;
    description: string;
    location: DtoCity;
    photo: string;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    characteristics?: ICharacteristics;
    regulations?: object;
    stress_resilience?: object;

    breeder: IBreeder = null;
    user: IUser = null;

    constructor(commodity: ICommodity) {
        super();
        this.table = 'commodities';

        this.id = commodity?.id;
        this.user_id = commodity?.user_id;
        this.name = commodity?.name;
        this.breeder_id = commodity?.breeder_id;
        this.scientific_name = commodity?.scientific_name;
        this.accession = commodity?.accession;
        this.yield = commodity?.yield;
        this.description = commodity?.description;
        this.photo = commodity?.photo;
        this.created_at = commodity?.created_at;
        this.updated_at = commodity?.updated_at;
        this.deleted_at = commodity?.deleted_at;
        this.regulations = commodity?.regulations;
        this.stress_resilience = commodity?.stress_resilience;

        if (commodity?.breeder)
            this.breeder = new DtoBreeder(commodity.breeder);

        if (commodity?.location)
            this.location = new DtoCity(commodity.location);

        if (commodity?.user)
            this.user = new DtoUser(commodity.user);

        if (commodity?.characteristics)
            this.characteristics = new DtoCharacteristics(commodity.characteristics);
    }

    get breederName()
    {
        // @ts-ignore
        return this.breeder.getFullName;
    }

    get getProfilePhoto() {
        return this.photo;
    }

    get type() {
        return this.breeder.breeder_type;
    }

    get breederAffiliation()
    {
        // @ts-ignore
        return this.breeder.getAffiliation;
    }

    get breederEmail()
    {
        // @ts-ignore
        return this.breeder.getEmail;
    }
    get breederMobileNo()
    {
        // @ts-ignore
        return this.breeder.getMobileNo;
    }

    get coordinates() {
        return this.location;
    }
}
