import IRole from "./IRole";
import IAccount from "./IAccount";

export default interface IUser {
    id: number;
    fname: string;
    mname?: string;
    lname: string;
    suffix?: string;
    email: string;
    affiliation?: string;
    mobile_no?: string;
    email_verified_at?: string;

    roles?: IRole[];
    accounts?: IAccount[];
}
