import IBaseResponse from "../../interface/base/IBaseResponse";

export default class DtoBaseResponse implements IBaseResponse {
    data: any;
    links: any;
    message: string | null;
    meta: any;
    show: boolean;
    timeout: number | null;
    title: string | null;
    type: string | null;

    constructor(params: IBaseResponse) {
        this.data = params.data;
        this.links = params.links;
        this.message = params.message;
        this.meta = params.meta;
        this.show = params.show;
        this.timeout = params.timeout;
        this.title = params.title;
        this.type = params.type;
    }


}
