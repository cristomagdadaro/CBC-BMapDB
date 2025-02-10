import IProject from "../interface/IProject";
import BaseClass from "../../../../Modules/core/domain/base/BaseClass";
import DtoInstitute from "@/Modules/core/dto/DtoInstitute";
import IInstitute from "@/Modules/core/interface/auth/IInstitute";
import IExpert from "@/Pages/Projects/TWG/interface/IExpert";
import DtoExpert from "@/Pages/Projects/TWG/dto/DtoExpert";

export default class DtoProject extends BaseClass implements IProject {
    id: number;
    user_id: number;
    title: string;
    objective: string;
    expected_output: string;
    project_leader: IExpert;
    funding_agency: string;
    duration: string;
    status: string;
    institution: number;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    affiliated: IInstitute;

    constructor(project: IProject) {
        super();
        this.table = 'projects';
        this.id = project.id;
        this.title = project.title;
        this.objective = project.objective;
        this.expected_output = project.expected_output;
        this.funding_agency = project.funding_agency;
        this.duration = project.duration;
        this.status = project.status;
        this.created_at = project.created_at;
        this.updated_at = project.updated_at;
        this.deleted_at = project.deleted_at;
        this.institution = project.institution;
        this.project_leader = new DtoExpert(project?.project_leader);
        this.affiliated = new DtoInstitute(project?.affiliated);
    }
}
