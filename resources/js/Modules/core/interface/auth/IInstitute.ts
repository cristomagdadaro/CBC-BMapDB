import ICity from "../location/ICity";
import IBaseClass from "../base/IBaseClass";

export default interface IInstitute extends IBaseClass{
    id: number;
    name: string;
    inst_type: string;
    location: ICity;
    website: string;
    email: string;
    phone: string;
}
