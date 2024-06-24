import {BaseClass} from "@/Modules/core/domain/BaseClass.js";

export default class Expert extends BaseClass{
    constructor(params = {}) {
        super();
        this.id = params.id ?? null;
        this.user_id = params.user_id ?? null;
        this.name = params.name ?? null;
        this.position = params.position ?? null;
        this.educ_level = params.educ_level ?? null;
        this.expertise = params.expertise ?? null;
        this.research_interest = params.research_interest ?? null;
        this.mobile = params.mobile ?? null;
        this.email = params.email ?? null;
        this.updated_at = params.updated_at ?? null;
        this.created_at = params.created_at ?? null;
        this.deleted_at = params.deleted_at ?? null;
    }

    /**
     * Get the hidden columns, that should not be displayed in the table columns and filter by dropdown
     * */
    static getHiddenColumns() {
        return ['user_id','id', 'updated_at', 'created_at', 'deleted_at'];
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
                title: 'User ID',
                key: 'user_id',
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
                title: 'Position',
                key: 'position',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Educ. Level',
                key: 'educ_level',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Expertise',
                key: 'expertise',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Research Interest',
                key: 'research_interest',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Mobile',
                key: 'mobile',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Email',
                key: 'email',
                align: 'center',
                sortable: false,
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
        ]
    }
}
