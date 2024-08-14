import {BaseClass} from "@/Modules/core/domain/BaseClass.js";

export default class Project extends BaseClass{
    constructor(params = {}) {
        super();
        this.id = params.id ?? null;
        this.twg_expert_id = params.twg_expert_id ?? null;
        this.title = params.title ?? null;
        this.objective = params.objective ?? null;
        this.expected_output = params.expected_output ?? null;
        this.project_leader = params.project_leader ?? null;
        this.funding_agency = params.funding_agency ?? null;
        this.duration = params.duration ?? null;
        this.status = params.status ?? null;
        this.updated_at = params.updated_at ?? null;
        this.created_at = params.created_at ?? null;
        this.deleted_at = params.deleted_at ?? null;
    }

    static getHiddenColumns() {
        return ['twg_expert_id','id', 'updated_at', 'created_at', 'deleted_at'];
    }

    static getColumns() {
        return [
            {
                title: 'ID',
                key: 'id',
                align: 'center',
                sortable: true,
                visible: true,
            },{
                title: 'Expert ID',
                key: 'twg_expert_id',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Title',
                key: 'title',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Objective',
                key: 'objective',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Expected Outputs',
                key: 'expected_output',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Project Leader',
                key: 'project_leader',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Funding Agency',
                key: 'funding_agency',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Duration',
                key: 'duration',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Status',
                key: 'status',
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
        ]
    }
}
