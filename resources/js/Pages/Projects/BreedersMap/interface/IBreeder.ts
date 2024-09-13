import IBaseClass from "../../../../Modules/core/interface/base/IBaseClass";
import IInstitute from "../../../../Modules/core/interface/auth/IInstitute";

export default interface IBreeder extends IBaseClass {
    id: number;
    user_id: number;
    name: string;
    affiliated: IInstitute;
    phone: string;
    email: string;
}
