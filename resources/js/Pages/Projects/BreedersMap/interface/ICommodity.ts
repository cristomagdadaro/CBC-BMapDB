import IBreeder from "./IBreeder";
import ICity from "../../../../Modules/core/interface/location/ICity";
import IBaseClass from "../../../../Modules/core/interface/base/IBaseClass";
import IUser from "@/Modules/core/interface/auth/IUser";
import ICharacteristics from "@/Pages/Projects/BreedersMap/interface/ICharacteristics";
import IAdditionalInfo from "@/Pages/Projects/BreedersMap/interface/IAdditionalInfo";

export default interface ICommodity  extends IBaseClass {
    id: number;
    user_id: number;
    name: string;
    breeder_id: number;
    scientific_name: string;
    accession: string;
    yield: string;
    description: string;
    photo: string;
    location: ICity;
    updated_at: string;
    created_at: string;
    deleted_at: string;
    approved_at?: string;

    characteristics?: ICharacteristics;
    additionalinfo?: IAdditionalInfo;
    regulations?: object;
    stress_resilience?: object;

    breeder: IBreeder;
    user: IUser;
}
