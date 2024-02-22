import {BaseClass} from "@/Modules/core/domain/BaseClass.js";

export default class Product extends BaseClass {
    constructor(params = {}) {
        super();
        this.id = params.id ?? null;
        this.name = params.name ?? null;
        this.brand = params.brand ?? null;
        this.purpose = params.purpose ?? null;
        this.cost = params.cost ?? null;
    }

    static toObject(obj) {
        return Object.assign({
            id: obj.id,
            name: obj.name,
            brand: obj.brand,
            purpose: obj.purpose,
            cost: obj.cost,
        }, obj);
    }

    static getColumns() {
        return [
            {
                title: 'ID',
                key: 'id',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Name',
                key: 'name',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Brand',
                key: 'brand',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Purpose',
                key: 'purpose',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Cost',
                key: 'cost',
                align: 'center',
                sortable: true,
                visible: true,
            },
        ];
    }
}
