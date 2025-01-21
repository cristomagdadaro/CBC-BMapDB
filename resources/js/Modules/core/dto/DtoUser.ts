import IUser from "../interface/auth/IUser";
import IRole from "../interface/auth/IRole";
import IAccount from "../interface/auth/IAccount";
import BaseClass from "../domain/base/BaseClass";
import IPermission from "../interface/auth/IPermission";
import DtoRole from "./DtoRole";
import DtoAccount from "./DtoAccount";
import DtoPermission from "./DtoPermission";
import DtoInstitute from "./DtoInstitute";
import User from "../../../Modules/core/domain/auth/User";
import {usePage} from "@inertiajs/vue3";

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

    password?: string;
    password_confirmation?: string;

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

        if (dto.password)
        {
            this.password = dto.password;
            this.password_confirmation = dto.password_confirmation;
        }

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

    get roleList() {
        return this.roles.map(role => {
            // name and id
            return {
                name: role.name,
                id: role.id
            };
        });
    }

    get rolePermissionsList() {
        return this.roles.map(role => {
            return role.permissions.map(permission => {
                return {
                    name: permission.name,
                    id: permission.id
                };
            });
            // @ts-ignore
        }).flat();
    }

    get userPermissionsList() {
        return this.permissions.map(permission => {
            return {
                name: permission.name,
                id: permission.id
            };
        });
    }

    get affiliation() {
        if (this.affiliated)
            return this.affiliated.name;
        return 'Unknown';
    }

    get isAdmin() {
        return this.roles.some(role => role.name === "Administrator");
    }

    get accountsCount(): number {
        if (this.accounts)
            return this.accounts.length;
        return 0;
    }

    get accountsList(): string[] {
        return this.accounts.map(account => {
            if (account.application)
                return account.application.name;
            return null;
        });
    }
}
