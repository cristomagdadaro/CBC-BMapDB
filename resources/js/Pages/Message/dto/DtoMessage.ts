import BaseClass from "@/Modules/core/domain/base/BaseClass";
import IMessage from "@/Pages/Message/interface/IMessage";
import IUser from "@/Modules/core/interface/auth/IUser";
import DtoUser from "@/Modules/core/dto/DtoUser";

export default class DtoMessage extends BaseClass implements IMessage {
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

    constructor(message: IMessage) {
        super(message);

        this.uuid = message?.uuid;
        this.message = message?.message;
        this.user_id = message?.user_id;
        this.from_id = message?.from_id;
        this.replied_to = message?.replied_to;
        this.created_at = message?.created_at;
        this.updated_at = message?.updated_at;
        this.deleted_at = message?.deleted_at;

        if (message?.to_user)
            this.to_user = new DtoUser(message.to_user);

        if (message?.from_user)
            this.from_user = new DtoUser(message.from_user);
    }

}
