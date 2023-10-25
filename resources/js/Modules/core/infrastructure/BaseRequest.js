export default class BaseRequest{
    constructor() {
        this.params = {
            page: 1,
            per_page: 10,
            sort: 'id',
            order: 'asc',
        };
    }

    addParam(key, value) {
        this.params[key] = value;
    }

    updateParam(key, value) {
        if(value === null || value === undefined || value === '' || value === false)
            this.removeParam(key);
        else
            this.params[key] = value;
    }

    getParam(key) {
        return this.params[key];
    }

    removeParam(key) {
        delete this.params[key];
    }

    toObject() {
        return this.params;
    }
}
