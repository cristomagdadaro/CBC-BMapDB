import DtoBaseResponse from "@/Modules/core/dto/base/DtoBaseResponse";

export default class BaseResponse extends DtoBaseResponse {

    constructor(params: any) {
        super(params);
    }

    static fromObject(response: DtoBaseResponse) {
        return new BaseResponse(response);
    }

    toObject() {
        return {
            data: this.data,
            meta: this.meta,
            links: this.links,
            notification: this.notification,
        }
    }
}
