import IPermission from "../interface/auth/IPermission";
import BaseClass from "../domain/base/BaseClass";

export default class DtoPermission extends BaseClass implements IPermission {
    id: number;
    name: string;
    guard_name: string;

    constructor(dto: IPermission) {
        super();
        this.table = 'permissions';
        this.id = dto.id;
        this.name = dto.name;
        this.guard_name = dto.guard_name;
    }
}
