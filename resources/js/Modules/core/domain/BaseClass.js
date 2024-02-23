export class BaseClass
{
    constructor(resp = {}) {
        Object.assign(this, resp);
    }

    static getClass() {
        return this.constructor.name;
    }

    static toObject(obj) {
        return Object.assign({}, obj);
    }

    static getColumns() {
        return [
            Object.keys(this.toObject(new this())).map((key) => {
                return {
                    title: key,
                    key: key,
                    align: 'left',
                    sortable: true,
                };
            })
        ];
    }
}
