import {BaseClass} from "@/Modules/core/domain/BaseClass.js";

export default class Expert extends BaseClass{
    constructor(params = {}) {
        super();
        this.id = params.id ?? null;
        this.name = params.name ?? null;
        this.position = params.position ?? null;
        this.educ_level = params.educ_level ?? null;
        this.expertise = params.expertise ?? null;
        this.research_interest = params.research_interest ?? null;
        this.mobile = params.mobile ?? null;
        this.email = params.email ?? null;
    }
    static toObject(obj) {
        return Object.assign({
            id: obj.id,
            name: obj.name,
            position: obj.position,
            educ_level: obj.educ_level,
            expertise: obj.expertise,
            research_interest: obj.research_interest,
            mobile: obj.mobile,
            email: obj.email,
        }, obj);
    }

    static getColumns() {
        return [
            {
                title: 'ID',
                key: 'id',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Name',
                key: 'name',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Position',
                key: 'position',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Degree',
                key: 'educ_level',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Expertise',
                key: 'expertise',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Research Interest',
                key: 'research_interest',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Mobile',
                key: 'mobile',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Email',
                key: 'email',
                align: 'center',
                sortable: false,
                visible: true,
            }
        ]
    }
}
