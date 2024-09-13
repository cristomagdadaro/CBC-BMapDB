import DtoBreeder from "../dto/DtoBreeder";

export default class Breeder extends DtoBreeder{
    constructor(params : DtoBreeder) {
        super(params);
        console.log(this);
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
                title: 'User ID',
                key: 'user_id',
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
                title: 'Affiliation',
                key: 'affiliated.name',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Phone',
                key: 'phone',
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
                title: 'City',
                key: 'affiliated.city',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Province',
                key: 'affiliated.province',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Region',
                key: 'affiliated.region',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Updated At',
                key: 'updated_at',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Created At',
                key: 'created_at',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Deleted At',
                key: 'deleted_at',
                align: 'center',
                sortable: true,
                visible: true,
            },
        ];
    }
}
