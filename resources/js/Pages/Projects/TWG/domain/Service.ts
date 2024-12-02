import DtoService from "../dto/DtoService";
import BaseClass from "../../../../Modules/core/domain/base/BaseClass";
import IService from "../interface/IService";
import IExpert from "../interface/IExpert";

export default class Service extends BaseClass implements IService{
    id: number;
    twg_expert_id: number;
    type: string;
    purpose: string;
    direct_beneficiaries: string;
    indirect_beneficiaries: string;
    officer_in_charge: string;
    cost: number;
    created_at: string;
    updated_at: string;
    deleted_at: string;
    expert: IExpert;

    constructor(params : DtoService) {
        super(params);

        this.indexUri = 'api.twg.services.index';
        this.showUri = 'api.twg.services..show';
        this.storeUri = 'api.twg.services.store';
        this.updateUri = 'api.twg.services.update';
        this.destroyUri = 'api.twg.services.destroy';
        this.multiDestroyUri = 'api.twg.services.destroy.multi';
        this.summaryUri = 'api.twg.services.summary';
    }

    static createForm() {
        return {
            twg_expert_id: null,
            type: null,
            purpose: null,
            direct_beneficiaries: null,
            indirect_beneficiaries: null,
            officer_in_charge: null,
            cost: null,
            created_at: null,
            updated_at: null,
            deleted_at: null,
            expert: null,
        }
    }

    static updateForm(oldValue: Partial<Service>) {
        return {
            id: oldValue.id ?? null,
            twg_expert_id: oldValue.twg_expert_id ?? null,
            type: oldValue.type ?? null,
            purpose: oldValue.purpose ?? null,
            direct_beneficiaries: oldValue.direct_beneficiaries ?? null,
            indirect_beneficiaries: oldValue.indirect_beneficiaries ?? null,
            officer_in_charge: oldValue.officer_in_charge ?? null,
            cost: oldValue.cost ?? null,
            created_at: oldValue.created_at ?? null,
            updated_at: oldValue.updated_at ?? null,
            deleted_at: oldValue.created_at ?? null,
            expert: oldValue.updated_at ?? null,
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
                title: 'Expert',
                key: 'twg_expert_id',
                db_key: 'twg_expert_id',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Expert',
                key: 'expert.name',
                db_key: 'name',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Type',
                key: 'type',
                db_key: 'type',
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
                title: 'Direct Beneficiaries',
                key: 'direct_beneficiaries',
                db_key: 'direct_beneficiaries',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Indirect Beneficiaries',
                key: 'indirect_beneficiaries',
                db_key: 'indirect_beneficiaries',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Officer In Charge',
                key: 'officer_in_charge',
                db_key: 'officer_in_charge',
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
