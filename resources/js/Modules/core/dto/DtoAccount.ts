import IAccount from "../interface/auth/IAccount";
import IApplication from "../interface/auth/IApplication";
import BaseClass from "../domain/base/BaseClass";
import IUser from "../interface/auth/IUser";
import DtoApplication from "./DtoApplication";
import DtoUser from "./DtoUser";

export default class DtoAccount extends BaseClass implements IAccount {
    id: number = null;
    user_id: number = null;
    app_id: number = null;
    approved_at: string = null;
    application: IApplication = null;
    user: IUser = null;

    constructor(dto: IAccount) {
        super();
        this.table = 'accounts';
        this.id = dto.id;
        this.user_id = dto.user_id;
        this.app_id = dto.app_id;
        this.approved_at = dto.approved_at;

        if (dto.application)
            this.application = new DtoApplication(dto.application);

        if (dto.user)
            this.user = new DtoUser(dto.user);
    }
}
