import IInstitute from "@/Modules/core/interface/auth/IInstitute";
import IExpert from "@/Pages/Projects/TWG/interface/IExpert";

export default interface IProject {
    id: number;
    user_id: number;
    title: string;
    objective: string;
    expected_output: string;
    project_leader: IExpert;
    funding_agency: string;
    institution: number;
    duration: string;
    status: string;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    affiliated: IInstitute;
}
