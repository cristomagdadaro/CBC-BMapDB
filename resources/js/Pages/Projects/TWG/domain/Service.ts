import DtoService from "../dto/DtoService";

export default class Service extends DtoService {

    constructor(params : DtoService) {
        super(params);
    }

    static getHiddenColumns() {
        return ['id', 'twg_expert_id', 'updated_at', 'created_at', 'deleted_at'];
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
                title: 'Expert',
                key: 'twg_expert_id',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Expert',
                key: 'expert.getFullName',
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
