import {BaseClass} from "@/Modules/core/domain/BaseClass.js";

export default class Project extends BaseClass{
    constructor(params = {}) {
        super();
        this.id = params.id ?? null;
        this.title = params.title ?? null;
        this.objective = params.objective ?? null;
        this.expected_output = params.expected_output ?? null;
        this.project_leader = params.project_leader ?? null;
        this.funding_agency = params.funding_agency ?? null;
        this.duration = params.duration ?? null;
        this.status = params.status ?? null;
    }
    static toObject(obj) {
        return Object.assign({
            id: obj.id,
            title: obj.title,
            objective: obj.objective,
            expected_output: obj.expected_output,
            project_leader: obj.project_leader,
            funding_agency: obj.funding_agency,
            duration: obj.duration,
            status: obj.status,
        }, obj);
    }

    static getColumns() {
        return [
            {
                title: 'ID',
                key: 'id',
                align: 'center',
                sortable: true,
            },
            {
                title: 'Title',
                key: 'title',
                align: 'center',
                sortable: true,
            },
            {
                title: 'Objective',
                key: 'objective',
                align: 'center',
                sortable: true,
            },
            {
                title: 'Expected Outputs',
                key: 'expected_output',
                align: 'center',
                sortable: true,
            },
            {
                title: 'Project Leader',
                key: 'project_leader',
                align: 'center',
                sortable: true,
            },
            {
                title: 'Funding Agency',
                key: 'funding_agency',
                align: 'center',
                sortable: true,
            },
            {
                title: 'Duration',
                key: 'duration',
                align: 'center',
                sortable: true,
            },
            {
                title: 'Status',
                key: 'status',
                align: 'center',
                sortable: true,
            },
        ]
    }
}