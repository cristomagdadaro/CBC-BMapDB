export default class BaseResponse {
    constructor({
            data = null,
            meta = null,
            links = null,
            title = null,
            message = null,
            type = null,
            timeout = 5000,
            show = false
        } = {}) {
        this.data = data;
        this.meta = meta;
        this.links = links;
        this.title = title;
        this.message = message;
        this.type = type;
        this.timeout = timeout;
        this.show = show;
    }

    static fromObject(response) {
        return new BaseResponse(response.data, response.meta, response.links);
    }

    toObject() {
        return {
            data: this.data,
            meta: this.meta,
            links: this.links
        }
    }
}
