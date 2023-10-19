import ApiService from "@/Modules/core/infrastructure/ApiService.js";

export default class CRCMDatatable
{
    constructor(columns, link) {
        this.api = new ApiService(link);
        this.columns = columns;
    }

    async refresh(params) {
        return await this.api.get(params);
    }

}
