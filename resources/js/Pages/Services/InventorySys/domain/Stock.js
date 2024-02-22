import { BaseClass } from "@/Modules/core/domain/BaseClass.js";

export class Stock extends BaseClass {
    constructor(params) {
        super();
        this.id = params.id;
        this.name = params.name;
        this.brand = params.brand;
        this.unit = params.unit;
        this.remaining_qnty = params.remaining_qnty;
        this.total_qnty = params.total_qnty;
    }

    static toObject(category) {
        return {
            id: category.id,
            name: category.name,
            brand: category.brand,
            unit: category.unit,
            remaining_qnty: category.remaining_qnty,
            total_qnty: category.total_qnty
        }
    }

    static getColumns() {
        return [
            {
                title: 'ID',
                key: 'id',
                align: 'center',
                sortable: true,
                visible: false,
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
                title: 'Unit',
                key: 'unit',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Total Quantity',
                key: 'total_qnty',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Remaining Quantity',
                key: 'remaining_qnty',
                align: 'center',
                sortable: true,
                visible: true,
            },
        ]
    }
}
