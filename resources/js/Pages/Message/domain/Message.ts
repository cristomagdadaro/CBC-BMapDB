import DtoMessage from "@/Pages/Message/dto/DtoMessage";


export default class Message extends DtoMessage {
    constructor(message: DtoMessage) {
        super(message);

        this.appendWith = ['toUser','fromUser'];
    }
}
