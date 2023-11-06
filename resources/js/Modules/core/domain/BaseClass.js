export class BaseClass
{
    constructor(resp = {}) {
        Object.assign(this, resp);
    }

    getClass() {
        return this.constructor.name;
    }

    static toObject(obj) {
        return Object.assign({}, obj);
    }
}
