import IProject from "../interface/IProject";
import BaseClass from "../../../../Modules/core/domain/base/BaseClass";
import Expert from "../domain/Expert";
import IExpert from "../interface/IExpert";

export default class DtoProject extends BaseClass implements IProject {
    id: number;
    twg_expert_id: number;
    title: string;
    objective: string;
    expected_output: string;
    project_leader: string;
    funding_agency: string;
    duration: string;
    status: string;
    created_at: string;
    updated_at: string;
    deleted_at: string;
    expert: IExpert;

    constructor(project: IProject) {
        super();
        this.id = project.id;
        this.twg_expert_id = project.twg_expert_id;
        this.title = project.title;
        this.objective = project.objective;
        this.expected_output = project.expected_output;
        this.project_leader = project.project_leader;
        this.funding_agency = project.funding_agency;
        this.duration = project.duration;
        this.status = project.status;
        this.created_at = project.created_at;
        this.updated_at = project.updated_at;
        this.deleted_at = project.deleted_at;

        if (project.expert)
            //@ts-ignore
            this.expert = new Expert(project.expert);
    }
}
