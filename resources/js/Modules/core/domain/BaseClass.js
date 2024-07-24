export class BaseClass extends Object
{
    constructor(resp = {}) {
        super();
        Object.assign(this, resp);
    }

    static getClass() {
        return this.constructor.name;
    }

    static toObject(obj) {
        return Object.assign({}, obj);
    }

    static makeAsOptions(options) {
        return options.map((option) => {
            return {
                value: option.id || option.value,
                label: option.name || option.title || option.label || option.value || option.id
            };
        });
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

    static getHiddenColumns() {
        return [];
    }
}
