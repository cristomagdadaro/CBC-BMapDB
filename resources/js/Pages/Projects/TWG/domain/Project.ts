import DtoProject from "../dto/DtoProject";

export default class Project extends DtoProject{
    constructor(params: DtoProject) {
        super(params);
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
                sortable: false,
                visible: false,
            },
            {
                title: 'Expert ID',
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
