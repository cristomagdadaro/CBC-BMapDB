import ApiService from "@/Modules/core/infrastructure/ApiService.js";
import {ref} from "vue";
import BaseRequest from "@/Modules/core/domain/base/BaseRequest.js";

export default class TWGApiService {
    constructor(baseUrl, model = Object) {
        this.api = new ApiService(baseUrl);
        this.model = model;
        this.request = new BaseRequest();
        const localParams = BaseRequest.getParamsLocal();
        this.request = localParams? new BaseRequest(localParams) : new BaseRequest();
    }

    async init() {
        await this.refresh();
    }

    async refresh() {
        this.response = await this.api.get(this.request.toObject(), this.model);
    }

    async UpdateParam(params){
        for (const key in params) {
            this.request.updateParam(key, params[key]);
        }
        await this.refresh();
    }
}
