import DtoBreeder from "../dto/DtoBreeder";

export default class Breeder extends DtoBreeder{
    constructor(params : DtoBreeder) {
        super(params);
        this.table = 'breeders';
        this.indexUri = 'api.breeders.index';
        this.showUri = 'api.breeders.show';
        this.storeUri = 'api.breeders.store';
        this.updateUri = 'api.breeders.update';
        this.destroyUri = 'api.breeders.destroy';
        this.multiDestroyUri = 'api.breeders.destroy.multi';
        this.summaryUri = 'api.breeders.summary';
    }

    static createForm()
    {
        return {
            user_id: null,
            name: null,
            phone: null,
            email: null,
            password: null,
            affiliation: null,
            geolocation: null,
            remember_token: null,
        }
    }

    static updateForm(oldValue: Partial<Breeder>)
    {
        return {
            id: oldValue.id ?? null,
            user_id: oldValue.user_id ?? null,
            name: oldValue.name ?? null,
            phone: oldValue.phone ?? null,
            email: oldValue.email ?? null,
            //@ts-ignore
            password: oldValue.password ?? null,
            affiliation: oldValue.affiliated ? oldValue.affiliated.id : null,
            geolocation: oldValue.location ? oldValue.location.id : null,
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
                title: 'Affiliation',
                key: 'affiliated.name',
                db_key: 'affiliated',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Phone',
                key: 'phone',
                db_key: 'phone',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Email',
                key: 'email',
                db_key: 'email',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Commodities',
                key: 'commoditiesCount',
                db_key: 'commodities_count',
                align: 'text-center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Address',
                key: 'location.getFullAddress',
                db_key: 'location',
                align: 'center',
                sortable: true,
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
        ];
    }
}
