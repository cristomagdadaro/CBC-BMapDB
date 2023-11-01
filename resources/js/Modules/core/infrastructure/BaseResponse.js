export default class BaseResponse {
    constructor(response = {}) {
        this.data = response.data;
        this.meta = response.meta;
        this.links = response.links;
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
