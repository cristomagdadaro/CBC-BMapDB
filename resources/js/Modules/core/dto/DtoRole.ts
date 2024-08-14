import IRole from "../interface/auth/IRole";
import Permission from "../domain/auth/Permission";
import IPermission from "../interface/auth/IPermission";
import BaseClass from "../domain/base/BaseClass";

export default class DtoRole extends BaseClass implements IRole {
    id: number = null;
    name: string = null;
    guard_name: string = null;
    permissions: IPermission[] = null;

    constructor(dto: IRole) {
        super();
        this.id = dto.id;
        this.name = dto.name;
        this.guard_name = dto.guard_name;
        if (dto.permissions)
            //@ts-ignore
            this.permissions = dto.permissions.map(permission => new Permission(permission));
    }
}
