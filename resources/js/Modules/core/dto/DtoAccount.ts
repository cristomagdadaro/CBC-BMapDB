import IAccount from "../interface/auth/IAccount";
import IApplication from "../interface/auth/IApplication";
import Application from "../domain/auth/Application";
import BaseClass from "../domain/base/BaseClass";
import User from "../domain/auth/User";
import IUser from "../interface/auth/IUser";

export default class DtoAccount extends BaseClass implements IAccount {
    id: number = null;
    user_id: number = null;
    app_id: number = null;
    approved_at: string = null;
    application: IApplication = null;
    user: IUser = null;

    constructor(dto: IAccount) {
        super();
        this.id = dto.id;
        this.user_id = dto.user_id;
        this.app_id = dto.app_id;
        this.approved_at = dto.approved_at;

        if (dto.application)
            //@ts-ignore
            this.application = new Application(dto.application);

        if (dto.user)
            //@ts-ignore
            this.user = new User(dto.user);
    }

}
