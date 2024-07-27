import IBaseRequest from "../../interface/base/IBaseRequest";

export default class DtoBaseRequest implements IBaseRequest {
    page: number;
    per_page: number;
    sort: string;
    order: string;

    search?: string;
    filter?: string;
    is_exact?: boolean;

    constructor(params : IBaseRequest = {
        page: 1,
        per_page: 10,
        sort: 'id',
        order: 'asc',
        search: null,
        filter: null,
        is_exact: null
    }) {
        this.page = params.page;
        this.per_page = params.per_page;
        this.sort = params.sort;
        this.order = params.order;

        // optional parameters
        this.search = params.search;
        this.filter = params.filter;
        this.is_exact = params.is_exact;
    }

    get getPerPage() {
        return this.per_page;
    }

    get getPage() {
        return this.page;
    }

    get getIsExact() {
        return this.is_exact;
    }

    get getSort() {
        return this.sort;
    }

    get getOrder() {
        return this.order;
    }

    get getSearch() {
        return this.search;
    }

    get getFilter() {
        return this.filter;
    }

    updateParam(key : string, value : any) {
        if(value === null || value === undefined || value === '' || value === false)
            this.removeParam(key);
        else
            this[key] = value;
    }

    removeParam(key: string) {
        delete this[key];
    }

    getParam(key: string) {
        return this[key];
    }

    toObject() {
        if (localStorage.getItem('params') !== null)
            return JSON.parse(localStorage.getItem('params'));
        else
            return this;
    }

    saveParamsLocal() {
        localStorage.setItem('params', JSON.stringify(this));
    }

    static getParamsLocal() {
        if (localStorage.getItem('params') !== null)
            return JSON.parse(localStorage.getItem('params'));
        else
            return new this();
    }
}
