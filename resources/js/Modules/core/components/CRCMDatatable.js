import ApiService from "@/Modules/core/infrastructure/ApiService.js";
import BaseRequest from "@/Modules/core/infrastructure/BaseRequest.js";

export default class CRCMDatatable
{
    constructor(columns, link) {
        this.api = new ApiService(link);
        this.columns = columns;
        this.request = new BaseRequest();
        this.response = null;
    }

    init() {
        return this.refresh(this.request.toObject());
    }

    async refresh(params) {
        this.response = await this.api.get(params);
        return this.response;
    }

    paginateFunc(params) {
        this.request.updateParam('page', params.page);
        return this.refresh(this.request.toObject());
    }

    sortFunc(params) {
        this.request.updateParam('sort', params.sort);
        this.request.updateParam('order', params.order ? 'desc' : 'asc');
        return this.refresh(this.request.toObject());
    }

    searchFunc(params) {
        this.request.updateParam('search', params);
        return this.refresh(this.request.toObject());
    }
}
