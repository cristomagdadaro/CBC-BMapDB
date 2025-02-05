import IUser from "@/Modules/core/interface/auth/IUser";

export default interface IMessage {
    uuid?: string;
    message: string;
    user_id: number;
    from_id: number;
    replied_to: string;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;

    to_user?: IUser;
    from_user?: IUser;
}
