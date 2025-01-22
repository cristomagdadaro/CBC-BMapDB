import IBreeder from "./IBreeder";
import ICity from "../../../../Modules/core/interface/location/ICity";
import IBaseClass from "../../../../Modules/core/interface/base/IBaseClass";
import IUser from "@/Modules/core/interface/auth/IUser";
import ICharacteristics from "@/Pages/Projects/BreedersMap/interface/ICharacteristics";
import ICommodityAdditionalInfo from "@/Pages/Projects/BreedersMap/interface/IAdditionalInfo";

export default interface ICommodity  extends IBaseClass {
    id: number;
    user_id: number;
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
    updated_at: string;
    created_at: string;
    deleted_at: string;

    characteristics?: ICharacteristics;
    additionalinfo?: ICommodityAdditionalInfo

    breeder: IBreeder;
    user: IUser;
}
