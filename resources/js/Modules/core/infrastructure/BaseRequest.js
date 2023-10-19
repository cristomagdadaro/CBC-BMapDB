export default class BaseRequest{
    constructor() {
        this.params = {};
    }

    addParam(key, value) {
        this.params[key] = value;
    }

    removeParam(key) {
        delete this.params[key];
    }

    toObject() {
        return this.params;
    }
}
