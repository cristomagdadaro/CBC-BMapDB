import ApiService from "@/Modules/core/infrastructure/ApiService.js";
import BaseRequest from "@/Modules/core/infrastructure/BaseRequest.js";
import BaseResponse from "@/Modules/core/infrastructure/BaseResponse.js";
import {ref} from "vue";

export default class CRCMDatatable
{
    constructor(baseUrl) {
        this.api = new ApiService(baseUrl);
        this.columns = ref([]);
        this.request = new BaseRequest();
        this.response = ref(new BaseResponse);
        this.processing = ref(false);
        this.selected = ref([]);
        this.model = ref(Object);
    }

    async init(model) {
        try {
            this.processing = true;
            this.response = await this.api.get(this.request.toObject(), this.model);
            this.columns = Object.keys(this.response['data'][0]);
            this.columns = this.formatColumns(this.columns);
            this.processing = false;
        } catch (error) {
            throw new Error(error);
        }
    }

    async refresh() {
        this.processing = true;
        this.response = await this.api.get(this.request.toObject(), this.model);
        this.processing = false;
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
        await this.refresh();
    }

    filterByColumn(params) {
        this.request.updateParam('filter', params.column);
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

    selectAll() {
        this.selected.push(...this.response['data'].map(item => item.id));
    }

    deselectAll() {
        this.selected = [];
    }

    async exportCSV() {
        // get all the rows
        this.request.updateParam('per_page', this.response['meta']['total'])
        let data = await this.api.get(this.request.toObject(), this.model);

        let columns = this.formatColumns(Object.keys(data['data'][0]));
        let csvContent = "data:text/csv;charset=utf-8,";

        csvContent += columns.map(column => column.label).join(",") + "\r\n";

        data['data'].forEach(function(rowArray){
            let row = Object.values(rowArray).join(",");
            csvContent += row + "\r\n";
        });

        let encodedUri = encodeURI(csvContent);
        let link = document.createElement("a");

        link.setAttribute("href", encodedUri);
        link.setAttribute("download", this.api.baseUrl + ".csv");

        document.body.appendChild(link);

        link.click();
    }

    importCSV() {
        /*let input = document.createElement('input');
        input.type = 'file';
        input.accept = '.csv';
        input.onchange = async () => {
            let file = input.files[0];
            let reader = new FileReader();
            reader.readAsText(file);
            reader.onload = async () => {
                let data = reader.result;
                let rows = data.split('\n');
                let columns = rows[0].split(',');
                let values = rows.slice(1).map(row => row.split(','));
                let model = this.model;
                let response = await this.api.post({columns, values}, model, 'import');
                this.response = response;
            };
            reader.onerror = () => {
                console.log(reader.error);
            };
        };
        input.click();*/
    }

    async delete(id) {
        await this.api.delete(id);
        await this.refresh();
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
