import ApiService from "@/Modules/core/infrastructure/ApiService.js";
import BaseRequest from "@/Modules/core/infrastructure/BaseRequest.js";
import BaseResponse from "@/Modules/core/infrastructure/BaseResponse.js";
import {ref} from "vue";
import Notification from "@/Components/Modal/Notification/Notification.js";
import { ErrorResponse } from "@/Pages/constants";
import {BaseClass} from "@/Modules/core/domain/BaseClass.js";

export default class CRCMDatatable
{
    constructor(baseUrl, model = BaseClass) {
        // api service class instance
        this.api = new ApiService(baseUrl);
        // array of columns to display
        this.columns = ref([]);
        // response from the server
        this.response = ref(new BaseResponse);
        // array of ids that are currently selected
        this.selected = ref([]);
        // class model that are current being handled in the CRMDatatable
        this.model = model;
        // array of ids to delete, can be multiple ids
        this.toDelete = ref([]);
        // when create or update, the modal will be forced to close after successful request
        this.closeAllModal = ref(false);
        // error bag from the server
        this.errorBag = ref();

        // retrieve params from local storage, if not found, create a new instance of BaseRequest
        // so that when the page is refreshed, the datatable will remember the last state
        const localParams = BaseRequest.getParamsLocal();
        this.request = localParams? new BaseRequest(localParams) : new BaseRequest();
    }

    async init() {
        this.response = await this.api.get(this.request.toObject(), this.model);
        if (this.response instanceof BaseResponse){
            this.getColumnsFromResponse(this.response);
            this.closeAllModal = true;
        }
        else {
            new Notification(this.response);
        }
    }

    get processing(){
        return this.api._processing;
    }

    set processing(value){
        this.api._processing = value;
    }

    async refresh() {
        await this.init();
    }

    async nextPage() {
        if(this.response['meta']['current_page'] + 1 <= this.response['meta']['last_page'])
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

    async gotoPage(page) {
        this.request.updateParam('page', page);
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

    isExactFilter(params) {
        this.request.updateParam('is_exact', params.is_exact);
    }

    async searchFunc(params) {
        this.request.updateParam('search', params.search);
        await this.refresh();
    }

    async perPageFunc(params){
        this.request.updateParam('per_page', params.per_page)
        if (this.response['meta']['last_page'] === this.response['meta']['current_page'])
            // if the current page is the last page, set the page to the last page
            this.request.updateParam('page', this.response['meta']['last_page']);
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
        //prevent duplicates
        this.selected = [...new Set([...this.selected, ...this.response['data'].map(item => item.id)])];
    }

    deselectAll() {
        this.selected = [];
    }

    async exportCSV() {
        let link = document.createElement("a");
        try {
            // Update per_page parameter to fetch all data in one request
            this.request.updateParam('per_page', this.response.meta.total);

            // Fetch data from API
            let response = await this.api.get(this.request.toObject(), this.model);
            let data = response.data;

            // Filter and map visible columns
            let columnsTitles = this.model.getColumns()
                .filter(column => column.visible !== false)
                .map(column => column.title);
            let columnKeys = this.model.getColumns()
                .filter(column => column.visible !== false)
                .map(column => column.key);
            // Prepare CSV content
            let csvContent = "data:text/csv;charset=utf-8,";

            // Add header row
            csvContent += columnsTitles.join(",") + "\r\n";
            // Add data rows
            data.forEach(function(rowArray) {
                let row = columnKeys.map(column => {
                    let value = rowArray[column];
                    // Check if the value contains a comma
                    if (typeof value === 'string' && value.includes(',')) {
                        // Encapsulate the value in double quotes
                        return `"${value}"`;
                    }
                    return value;
                }).join(",");
                csvContent += row + "\r\n";
            });


            // Encode CSV content
            let encodedUri = encodeURI(csvContent);

            // Create download link
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `${new Date().toISOString().replace(/:/g, "-").replace(/\..+/, '')}.csv`);

            // Append link to body and trigger download
            document.body.appendChild(link);
            link.click();

            setTimeout(() => {
                new Notification('Success', "Exported successfully", 'success', 5000, true);
            }, 3000);
        } catch (error) {
            new Notification('Failed', "Failed to export data", 'failed', 5000, true);
        } finally {
            // Clean up: remove link from body
            if (link) {
                document.body.removeChild(link);
            }
        }
    }


    async importCSV(data) {
        let success = 0;
        let failed = 0;
        let total = 0;
        console.log(data);
        for (const row of data) {
            const response = await this.api.post(this.model.toObject(row));
            if (response instanceof BaseResponse){
                success++;
            }
            else if (ErrorResponse.some(error => response instanceof error)){
                failed++;
            }
            total++;
        }

        if (success === total)
            new Notification('Success', `Imported ${total} rows successfully`, 'success', 5000, true);

        else if (failed > 0 && success > 0 && success < total)
            new Notification('Success', `Imported ${success} out of ${total} successfully`, 'success', 5000, true);

        else if (failed === total)
            new Notification('Failed', `Failed to import all ${total} rows`, 'failed', 5000, true);
        await this.refresh();
        this.errorBag = {};
    }

    async create(data) {
        const response = await this.api.post(this.model.toObject(data));

        Notification.pushNotification(response);
        if (ErrorResponse.some(error => response instanceof error)){
            this.errorBag = response.toObject();
            return;
        }

        await this.refresh();
        this.errorBag = {};
    }

    async delete(id) {
        const response = await this.api.delete(id);

        Notification.pushNotification(response);

        if (ErrorResponse.some(error => response instanceof error)){
            this.errorBag = response.toObject();
            return;
        }
        await this.refresh();
        this.selected = this.selected.filter(item => item !== id);
    }

    async update(data) {
        const response = await this.api.put(this.model.toObject(data));

        Notification.pushNotification(response);

        if (ErrorResponse.some(error => response instanceof error)){
            this.errorBag = response.toObject();
        } else {
          await this.refresh();
          this.errorBag = {};
        }

        this.processing = false;        
    }

    async deleteSelected() {
        const response = await this.api.delete(this.selected);

        Notification.pushNotification(response);

        if (ErrorResponse.some(error => response instanceof error)){
            this.errorBag = response.toObject();
            return;
        }

        await this.refresh();
        this.selected = [];
    }

    getColumnsFromResponse(response) {
        if (response['data'].length > 0)
        {
            this.columns = Object.keys(response['data'][0]);
            // only include columns that are not in the hidden columns, which is in the this.model.getHiddenColumns()
            this.columns = this.columns.filter(column => !this.model.getHiddenColumns().includes(column));
            this.columns = this.formatColumns(this.columns);
            // store columns in the local storage with the current url as key
            localStorage.setItem(window.location.pathname, JSON.stringify(this.columns));
        }
        else if (localStorage.getItem(window.location.pathname))
            this.columns = JSON.parse(localStorage.getItem(window.location.pathname)
        );
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
