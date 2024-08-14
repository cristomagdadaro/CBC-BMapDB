import DtoExpert from "../dto/DtoExpert";

export default class Expert extends DtoExpert{
    constructor(params: DtoExpert) {
        super(params);
    }

    static getColumns() {
        return [
            {
                title: 'ID',
                key: 'id',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'User ID',
                key: 'user_id',
                align: 'center',
                sortable: true,
                visible: false,
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
                title: 'Educ. Level',
                key: 'educ_level',
                align: 'center',
                sortable: true,
                visible: false,
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
            },
            {
                title: 'Updated At',
                key: 'updated_at',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Created At',
                key: 'created_at',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Deleted At',
                key: 'deleted_at',
                align: 'center',
                sortable: true,
                visible: false,
            },
        ]
    }
}
