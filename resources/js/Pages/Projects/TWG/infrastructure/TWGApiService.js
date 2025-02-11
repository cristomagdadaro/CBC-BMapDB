import ApiService from "@/Modules/core/infrastructure/ApiService.ts";
import BaseRequest from "@/Modules/core/domain/base/BaseRequest.ts";

export default class TWGApiService {
    constructor(baseUrl, model = Object) {
        this.api = new ApiService(baseUrl);
        this.model = model;

        const localParams = BaseRequest.getParamsLocal();
        this.request = localParams? new BaseRequest(localParams) : new BaseRequest();
    }

    async init() {
        await this.refresh();
    }

    async refresh() {
        this.response = await this.api.get(this.request.toObject(), this.model);
    }
}
