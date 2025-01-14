import IUser from "../interface/auth/IUser";
import IRole from "../interface/auth/IRole";
import IAccount from "../interface/auth/IAccount";
import BaseClass from "../domain/base/BaseClass";
import IPermission from "../interface/auth/IPermission";
import DtoRole from "./DtoRole";
import DtoAccount from "./DtoAccount";
import DtoPermission from "./DtoPermission";
import DtoInstitute from "./DtoInstitute";

export default class DtoUser extends BaseClass implements IUser {
    id?: number = null;
    fname: string = null;
    mname: string = null;
    lname: string = null;
    suffix: string = null;
    email: string = null;
    mobile_no: string = null;
    email_verified_at: string = null;

    roles?: IRole[] = [];
    accounts?: IAccount[] = [];
    permissions?: IPermission[] = [];
    affiliated: DtoInstitute = null;

    constructor(dto: IUser) {
        super();
        this.table = 'users';
        this.id = dto.id;
        this.fname = dto.fname;
        this.mname = dto.mname;
        this.lname = dto.lname;
        this.suffix = dto.suffix;
        this.email = dto.email;
        this.affiliated = dto.affiliated;
        this.mobile_no = dto.mobile_no;
        this.email_verified_at = dto.email_verified_at;

        if (dto.roles)
            this.roles = dto.roles.map(role => new DtoRole(role));
        if (dto.accounts)
            this.accounts = dto.accounts.map(account => new DtoAccount(account));
        if (dto.permissions)
            this.permissions = dto.permissions.map(permission => new DtoPermission(permission));
        if (dto.affiliated)
            this.affiliated = new DtoInstitute(dto.affiliated);
    }

    get getRole() {
        return this.roles.map(role => role.name).join(", ");
    }
}
