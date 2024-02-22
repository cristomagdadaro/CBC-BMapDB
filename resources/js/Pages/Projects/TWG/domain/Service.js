import {BaseClass} from "@/Modules/core/domain/BaseClass.js";

export default class Service extends BaseClass {

    constructor(params = {}) {
        super();
        this.id = params.id ?? null;
        this.type = params.type ?? null;
        this.purpose = params.purpose ?? null;
        this.direct_beneficiaries = params.direct_beneficiaries ?? null;
        this.indirect_beneficiaries = params.indirect_beneficiaries ?? null;
        this.officer_in_charge = params.officer_in_charge ?? null;
        this.cost = params.cost ?? null;
    }

    static toObject(obj) {
        return Object.assign({
            id: obj.id,
            type: obj.type,
            purpose: obj.purpose,
            direct_beneficiaries: obj.direct_beneficiaries,
            indirect_beneficiaries: obj.indirect_beneficiaries,
            officer_in_charge: obj.officer_in_charge,
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
                title: 'Type',
                key: 'type',
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
                title: 'Direct Beneficiaries',
                key: 'direct_beneficiaries',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Indirect Beneficiaries',
                key: 'indirect_beneficiaries',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Officer In Charge',
                key: 'officer_in_charge',
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
