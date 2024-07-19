import {BaseClass} from "@/Modules/core/domain/BaseClass.js";

export default class Product extends BaseClass {
    constructor(params = {}) {
        super();
        this.id = params.id ?? null;
        this.name = params.name ?? null;
        this.brand = params.brand ?? null;
        this.purpose = params.purpose ?? null;
        this.cost = params.cost ?? null;
        this.updated_at = params.updated_at ?? null;
        this.created_at = params.created_at ?? null;
        this.deleted_at = params.deleted_at ?? null;
    }

    static getHiddenColumns() {
        return ['id', 'updated_at', 'created_at', 'deleted_at'];
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
            {
                title: 'Updated At',
                key: 'updated_at',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Created At',
                key: 'created_at',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Deleted At',
                key: 'deleted_at',
                align: 'center',
                sortable: true,
                visible: false,
            },
        ];
    }
}
