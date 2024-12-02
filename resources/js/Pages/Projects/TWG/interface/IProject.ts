import IExpert from "./IExpert";

export default interface IProject {
    id: number;
    user_id: number;
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
}
