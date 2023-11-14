import {ValidationErrorResponse} from "@/Modules/core/infrastructure/ValidationErrorResponse.js";
import {NotFoundErrorResponse} from "@/Modules/core/infrastructure/NotFoundErrorResponse.js";

export default class BaseResponse {
    constructor(response = {}) {
        this.data = response.data;
        this.meta = response.meta;
        this.links = response.links;

        this.title = response.title || 'Success';
        this.message = response.message || "Deleted successfully";
        this.type = response.type || 'success';
        this.timeout = response.timeout || 5000;
        this.show = response.show || true;
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
