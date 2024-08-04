import IUser from "../interface/auth/IUser";
import Role from "../domain/auth/Role";
import Account from "../domain/auth/Account";
import Permission from "../domain/auth/Permission";
import IRole from "../interface/auth/IRole";
import IAccount from "../interface/auth/IAccount";
import BaseClass from "../domain/base/BaseClass";
import IPermission from "../interface/auth/IPermission";

export default class DtoUser extends BaseClass implements IUser {
    id?: number = null;
    fname: string = null;
    mname: string = null;
    lname: string = null;
    suffix: string = null;
    email: string = null;
    affiliation: string = null;
    mobile_no: string = null;
    email_verified_at: string = null;

    roles?: IRole[] = [];
    accounts?: IAccount[] = [];
    permissions?: IPermission[] = [];

    constructor(dto: IUser) {
        super();
        this.id = dto.id;
        this.fname = dto.fname;
        this.mname = dto.mname;
        this.lname = dto.lname;
        this.suffix = dto.suffix;
        this.email = dto.email;
        this.affiliation = dto.affiliation;
        this.mobile_no = dto.mobile_no;
        this.email_verified_at = dto.email_verified_at;

        if (dto.roles)
            //@ts-ignore
            this.roles = dto.roles.map(role => new Role(role));
        if (dto.accounts)
            //@ts-ignore
            this.accounts = dto.accounts.map(account => new Account(account));
        if (dto.permissions)
            //@ts-ignore
            this.permissions = dto.permissions.map(permission => new Permission(permission));
    }


}
