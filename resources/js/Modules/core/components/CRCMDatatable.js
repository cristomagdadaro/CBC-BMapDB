import ApiService from "@/Modules/core/infrastructure/ApiService.js";
import BaseRequest from "@/Modules/core/infrastructure/BaseRequest.js";
import BaseResponse from "@/Modules/core/infrastructure/BaseResponse.js";
import {ref} from "vue";

export default class CRCMDatatable
{
    constructor(link) {
        this.api = new ApiService(link);
        this.columns = ref([]);
        this.request = new BaseRequest();
        this.response = ref(new BaseResponse);
        this.processing = ref(false);
        this.selected = ref([]);
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

    async refresh() {
        this.response = await this.api.get(this.request.toObject());
    }

    async nextPage() {
        this.request.updateParam('page', this.response['meta']['current_page'] + 1);
        await this.refresh();
    }

    async prevPage() {
        this.request.updateParam('page', this.response['meta']['current_page'] - 1);
        await this.refresh();
    }

    async firstPage() {
        this.request.updateParam('page', 1);
        await this.refresh();
    }

    async lastPage() {
        this.request.updateParam('page', this.response['meta']['last_page']);
        await this.refresh();
    }

    async sortFunc(params) {
        this.request.updateParam('sort', params.sort);
        this.request.updateParam('order', this.request.getParam('order') === 'asc' ? 'desc' : 'asc');
        this.response = await this.refresh();
    }

    async searchFunc(params) {
        this.request.updateParam('search', params.search);
        await this.refresh();
    }

    async perPageFunc(params){
        this.request.updateParam('per_page', params.per_page)
        await this.refresh();
    }

    addSelected(id) {
        if(!this.isSelected(id))
            this.selected.push(id);
        else
            this.removeSelected(id);
    }

    removeSelected(id) {
        this.selected = this.selected.filter(item => item !== id);
    }

    isSelected(id) {
        return this.selected.includes(id);
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
