import DtoProduct from "../dto/DtoProduct";
import BaseClass from "../../../../Modules/core/domain/base/BaseClass";
import IProduct from "../interface/IProduct";
import IExpert from "../interface/IExpert";

export default class Product extends BaseClass implements IProduct{
    id: number;
    twg_expert_id: number;
    name: string;
    brand: string;
    purpose: string;
    cost: number;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    expert: IExpert;

    constructor(params: DtoProduct) {
        super(params);

        this.indexUri = 'api.twg.products.index';
        this.showUri = 'api.twg.products..show';
        this.storeUri = 'api.twg.products.store';
        this.updateUri = 'api.twg.products.update';
        this.destroyUri = 'api.twg.products.destroy';
        this.multiDestroyUri = 'api.twg.products.destroy.multi';
        this.summaryUri = 'api.twg.products.summary';
    }

    static createForm() {
        return {
            twg_expert_id: null,
            name: null,
            brand: null,
            purpose: null,
            cost: null,
            created_at: null,
            updated_at: null,
            deleted_at: null,
            expert: null,
        }
    }

    static updateForm(oldValue: Partial<Product>) {
        return {
            id: oldValue.id ?? null,
            twg_expert_id: oldValue.twg_expert_id ?? null,
            name: oldValue.name ?? null,
            brand: oldValue.brand ?? null,
            purpose: oldValue.purpose ?? null,
            cost: oldValue.cost ?? null,
            created_at: oldValue.created_at ?? null,
            updated_at: oldValue.updated_at ?? null,
            deleted_at: oldValue.deleted_at ?? null,
            expert: oldValue.expert ?? null
        }
    }

    static getColumns() {
        return [
            {
                title: 'ID',
                key: 'id',
                db_key: 'id',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Expert ID',
                key: 'twg_expert_id',
                db_key: 'twg_expert_id',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Expert',
                key: 'expert.getFullName',
                db_key: 'twg_expert_id',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Name',
                key: 'name',
                db_key: 'name',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Brand',
                key: 'brand',
                db_key: 'brand',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Purpose',
                key: 'purpose',
                db_key: 'purpose',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Cost',
                key: 'cost',
                db_key: 'cost',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Updated At',
                key: 'updated_at',
                db_key: 'updated_at',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Created At',
                key: 'created_at',
                db_key: 'created_at',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Deleted At',
                key: 'deleted_at',
                db_key: 'deleted_at',
                align: 'center',
                sortable: true,
                visible: false,
            },
        ];
    }
}
