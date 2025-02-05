import IExpert from "../interface/IExpert";
import BaseClass from "../../../../Modules/core/domain/base/BaseClass";
import DtoExpert from "../dto/DtoExpert";
import IInstitute from "@/Modules/core/interface/auth/IInstitute";

export default class Expert extends BaseClass implements IExpert {
    id: number;
    user_id: number;
    name: string;
    position: string;
    educ_level: string;
    expertise: string;
    institution: number;
    research_interest: string;
    mobile: string;
    email: string;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    affiliated: IInstitute;

    constructor(params: DtoExpert) {
        super(params);

        this.indexUri = 'api.twg.experts.index';
        this.showUri = 'api.twg.experts.show';
        this.storeUri = 'api.twg.experts.store';
        this.updateUri = 'api.twg.experts.update';
        this.destroyUri = 'api.twg.experts.destroy';
        this.multiDestroyUri = 'api.twg.experts.destroy.multi';
        this.summaryUri = 'api.twg.experts.summary';

        this.appendWith = ['affiliated'];
    }

    static createForm() {
        return {
            user_id: null,
            name: null,
            position: null,
            educ_level: null,
            expertise: null,
            research_interest: null,
            mobile: null,
            email: null,
            institution: null,
        }
    }

    static updateForm(oldValue: Partial<Expert>) {
        return {
            id: oldValue.id ?? null,
            user_id: oldValue.user_id ?? null,
            name: oldValue.name ?? null,
            position: oldValue.position ?? null,
            educ_level: oldValue.educ_level ?? null,
            expertise: oldValue.expertise ?? null,
            research_interest: oldValue.research_interest ?? null,
            mobile: oldValue.mobile ?? null,
            email: oldValue.email ?? null,
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
                sortable: true,
                visible: false,
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
                title: 'User ID',
                key: 'user_id',
                db_key: 'user_id',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Name',
                key: 'name',
                db_key: 'name',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Position',
                key: 'position',
                db_key: 'position',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Educ. Level',
                key: 'educ_level',
                db_key: 'educ_level',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Expertise',
                key: 'expertise',
                db_key: 'expertise',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Research Interest',
                key: 'research_interest',
                db_key: 'research_interest',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Mobile',
                key: 'mobile',
                db_key: 'mobile',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Email',
                key: 'email',
                db_key: 'email',
                align: 'center',
                sortable: false,
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
