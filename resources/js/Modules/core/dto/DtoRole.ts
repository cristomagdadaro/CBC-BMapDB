import IRole from "../interface/auth/IRole";
import IPermission from "../interface/auth/IPermission";
import BaseClass from "../domain/base/BaseClass";
import DtoPermission from "./DtoPermission";

export default class DtoRole extends BaseClass implements IRole {
    id: number = null;
    name: string = null;
    guard_name: string = null;
    permissions: IPermission[] = null;

    constructor(dto: IRole) {
        super();
        this.table = 'roles';
        this.id = dto.id;
        this.name = dto.name;
        this.guard_name = dto.guard_name;
        if (dto.permissions)
            this.permissions = dto.permissions.map(permission => new DtoPermission(permission));
    }

    get getPermissions() {
        return this.permissions.map(permission => permission.name).join(', ');
    }
}
