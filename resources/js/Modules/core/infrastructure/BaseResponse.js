export default class BaseResponse {
    constructor(response = {}) {
        this.data = response.data;
        this.meta = response.meta;
        this.links = response.links;
    }

    static fromResponse(response) {
        return new BaseResponse(response.data, response.meta, response.links);
    }

    static fromData(data) {
        return new BaseResponse(data.data, data.meta, data.links);
    }

    toObject() {
        return {
            data: this.data,
            meta: this.meta,
            links: this.links
        }
    }
}
