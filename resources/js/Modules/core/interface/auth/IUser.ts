import IRole from "./IRole";
import IAccount from "./IAccount";
import IPermission from "./IPermission";
import DtoInstitute from "../../dto/DtoInstitute";

export default interface IUser {
    id?: number;
    fname: string;
    mname?: string;
    lname: string;
    suffix?: string;
    email: string;
    mobile_no?: string;
    email_verified_at?: string;

    roles?: IRole[];
    accounts?: IAccount[];
    permissions?: IPermission[];
    affiliated: DtoInstitute;
}
