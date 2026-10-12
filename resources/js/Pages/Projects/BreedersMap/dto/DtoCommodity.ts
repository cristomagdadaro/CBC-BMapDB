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
    institute_id: number;
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
    approved_at?: string;

    characteristics?: ICharacteristics;
    additionalinfo?: IAdditionalInfo;
    regulations?: object;
    stress_resilience?: object;

    breeder: IBreeder = null;
    institute: any = null;
    user: IUser = null;

    constructor(commodity: ICommodity) {
        super();
        this.table = 'commodities';

        this.id = commodity?.id;
        this.user_id = commodity?.user_id;
        this.name = commodity?.name;
        this.breeder_id = commodity?.breeder_id;
        this.institute_id = commodity?.institute_id;
        this.scientific_name = commodity?.scientific_name;
        this.accession = commodity?.accession;
        this.yield = commodity?.yield;
        this.description = commodity?.description;
        this.photo = commodity?.photo;
        this.created_at = commodity?.created_at;
        this.updated_at = commodity?.updated_at;
        this.deleted_at = commodity?.deleted_at;
        this.approved_at = commodity?.approved_at;
        this.regulations = commodity?.regulations;
        this.stress_resilience = commodity?.stress_resilience;

        if (commodity?.breeder)
            this.breeder = new DtoBreeder(commodity.breeder);

        if (commodity?.institute)
            this.institute = commodity.institute;

        if (commodity?.location)
            this.location = new DtoCity(commodity.location);

        if (commodity?.user)
            this.user = new DtoUser(commodity.user);

        if (commodity?.characteristics)
            this.characteristics = new DtoCharacteristics(commodity.characteristics);

        if (commodity?.additionalinfo)
            this.additionalinfo = new DtoAdditionalInfo(commodity.additionalinfo);
    }

    get breederName()
    {
        if (this.breeder) {
            // @ts-ignore
            return this.breeder.getFullName;
        }
        if (this.institute) {
            return this.institute.name;
        }
        return 'N/A';
    }

    get getProfilePhoto() {
        return this.photo;
    }

    get type() {
        if (this.breeder) return this.breeder.breeder_type;
        if (this.institute) return 'Institute';
        return 'N/A';
    }

    get breederAffiliation()
    {
        if (this.breeder) {
            // @ts-ignore
            return this.breeder.getAffiliation;
        }
        if (this.institute) {
            return this.institute.name;
        }
        return 'N/A';
    }

    get breederEmail()
    {
        if (this.breeder) {
            // @ts-ignore
            return this.breeder.getEmail;
        }
        if (this.institute) {
            return this.institute.email;
        }
        return 'N/A';
    }

    get breederMobileNo()
    {
        if (this.breeder) {
            // @ts-ignore
            return this.breeder.getMobileNo;
        }
        if (this.institute) {
            return this.institute.phone || this.institute.contact_no || 'N/A';
        }
        return 'N/A';
    }

    get coordinates() {
        return this.location;
    }
}
