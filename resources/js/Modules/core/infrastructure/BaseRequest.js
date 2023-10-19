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
        this.params[key] = value;
    }

    removeParam(key) {
        delete this.params[key];
    }

    toObject() {
        return this.params;
    }
}
