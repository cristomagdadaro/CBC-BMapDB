import IInstitute from "@/Modules/core/interface/auth/IInstitute";

export default interface IExpert {
    id: number;
    user_id: number;
    name: string;
    position: string;
    educ_level: string;
    expertise: string;
    research_interest: string;
    mobile: string;
    email: string;
    institution: number;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    affiliated: IInstitute;
}
