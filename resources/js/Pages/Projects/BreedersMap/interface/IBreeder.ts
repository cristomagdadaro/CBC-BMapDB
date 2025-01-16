import IBaseClass from "../../../../Modules/core/interface/base/IBaseClass";
import IInstitute from "../../../../Modules/core/interface/auth/IInstitute";
import ICommodity from "./ICommodity";
import ICity from "../../../../Modules/core/interface/location/ICity";

export default interface IBreeder extends IBaseClass {
    id?: number;
    user_id: number;
    fname: string;
    lname: string;
    mname: string;
    suffix: string;
    affiliated: IInstitute;
    mobile_no: string;
    email: string;

    commodities: ICommodity[];
    commodities_count?: number;
    location: ICity;

    created_at: string;
    updated_at: string;
    deleted_at: string;
}
