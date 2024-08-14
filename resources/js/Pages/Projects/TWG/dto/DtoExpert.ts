import IExpert from "../interface/IExpert";
import BaseClass from "../../../../Modules/core/domain/base/BaseClass";

export default class DtoExpert extends BaseClass implements IExpert {
    id: number;
    user_id: number;
    name: string;
    position: string;
    educ_level: string;
    expertise: string;
    research_interest: string;
    mobile: string;
    email: string;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    constructor(expert: IExpert) {
        super();
        this.id = expert.id;
        this.user_id = expert.user_id;
        this.name = expert.name;
        this.position = expert.position;
        this.educ_level = expert.educ_level;
        this.expertise = expert.expertise;
        this.research_interest = expert.research_interest;
        this.mobile = expert.mobile;
        this.email = expert.email;
        this.created_at = expert.created_at;
        this.updated_at = expert.updated_at;
        this.deleted_at = expert.deleted_at;
    }
}
