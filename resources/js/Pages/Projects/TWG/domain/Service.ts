import DtoService from "../dto/DtoService";
import BaseClass from "../../../../Modules/core/domain/base/BaseClass";
import IService from "../interface/IService";
import IExpert from "../interface/IExpert";
import IInstitute from "@/Modules/core/interface/auth/IInstitute";

export default class Service extends BaseClass implements IService{
    id: number;
    user_id: number;
    type: string;
    purpose: string;
    direct_beneficiaries: string;
    indirect_beneficiaries: string;
    cost: number;
    institution: number;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    officer_in_charge: IExpert;
    affiliated: IInstitute;

    constructor(params : DtoService) {
        super(params);

        this.indexUri = 'api.twg.services.index';
        this.showUri = 'api.twg.services.show';
        this.storeUri = 'api.twg.services.store';
        this.updateUri = 'api.twg.services.update';
        this.destroyUri = 'api.twg.services.destroy';
        this.multiDestroyUri = 'api.twg.services.destroy.multi';
        this.summaryUri = 'api.twg.services.summary';

        this.appendWith = ['affiliated', 'officerInCharge'];
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
            institution: null,
        }
    }

    static updateForm(oldValue: Partial<Service>) {
        return {
            id: oldValue.id ?? null,
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
            institution: oldValue.institution ?? null,
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
                title: 'Institute',
                key: 'affiliated.name',
                db_key: 'institution',
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
                key: 'officer_in_charge.name',
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
