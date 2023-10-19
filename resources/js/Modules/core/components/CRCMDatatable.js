import ApiService from "@/Modules/core/infrastructure/ApiService.js";
import BaseRequest from "@/Modules/core/infrastructure/BaseRequest.js";
import BaseResponse from "@/Modules/core/infrastructure/BaseResponse.js";

export default class CRCMDatatable
{
    constructor(columns, link) {
        this.api = new ApiService(link);
        this.columns = columns;
        this.request = new BaseRequest();
        this.response = new BaseResponse();
    }

    async init() {
        try {
            this.response = await this.api.get(this.request.toObject());
            this.columns = Object.keys(this.response['data'][0]);
            this.columns = this.formatColumns(this.columns);
        } catch (error) {
            throw new Error(error);
        }
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
        console.log(this.request.toObject());
        return this.refresh(this.request.toObject());
    }

    formatColumnName = (columnName) => {
        return columnName.replace(/_/g, ' ').replace(/\w\S*/g, (w) => (w.replace(/^\w/, (c) => c.toUpperCase())));
    }

    formatColumns = (columns) => {
        return columns.map(column => {
            return {name: column, label: this.formatColumnName(column), sortable: true}
        });
    }
}
