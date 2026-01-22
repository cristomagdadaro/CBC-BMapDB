import DtoProject from "../dto/DtoProject";

export default class Project extends DtoProject {
    constructor(params: DtoProject) {
        super(params);

        this.appendWith = ['affiliated','projectLeader'];
    }

    static createForm() {
        return {
            title: null,
            objective: null,
            expected_output: null,
            project_leader: null,
            funding_agency: null,
            duration: null,
            status: null,
            created_at: null,
            updated_at: null,
            deleted_at: null,
            expert: null,
            institution: null
        }
    }

    static updateForm(oldValue: Partial<Project>) {
        return {
            id: oldValue.id ?? null,
            title: oldValue.title ?? null,
            objective: oldValue.objective ?? null,
            expected_output: oldValue.expected_output ?? null,
            project_leader: oldValue.project_leader?.id ?? null,
            funding_agency: oldValue.funding_agency ?? null,
            duration: oldValue.duration ?? null,
            status: oldValue.status ?? null,
            created_at: oldValue.created_at ?? null,
            updated_at: oldValue.updated_at ?? null,
            deleted_at: oldValue.deleted_at ?? null,
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
                sortable: false,
                visible: false,
            },
            {
                title: 'Project Leader',
                key: 'project_leader.name',
                db_key: 'project_leader',
                align: 'center',
                sortable: true,
                visible: true,
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
                title: 'Title',
                key: 'title',
                db_key: 'title',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Objective',
                key: 'objective',
                db_key: 'objective',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Expected Outputs',
                key: 'expected_output',
                db_key: 'expected_output',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Funding Agency',
                key: 'funding_agency',
                db_key: 'funding_agency',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Duration',
                key: 'duration',
                db_key: 'duration',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Status',
                key: 'status',
                db_key: 'status',
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
        ]
    }
}
