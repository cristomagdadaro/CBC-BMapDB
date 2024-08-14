import IApplication from "./IApplication";
import IUser from "./IUser";

export default interface IAccount {
    id: number;
    user_id: number;
    app_id: number;
    approved_at: string;

    application: IApplication;
    user: IUser;
}
