import DtoBreeder from "../dto/DtoBreeder";
import User from "@/Modules/core/domain/auth/User";
import {usePage} from "@inertiajs/vue3";

export default class Breeder extends DtoBreeder{
    constructor(params : DtoBreeder) {
        super(params);
        this.indexUri = 'api.breeders.index';
        this.showUri = 'api.breeders.show';
        this.storeUri = 'api.breeders.store';
        this.updateUri = 'api.breeders.update';
        this.destroyUri = 'api.breeders.destroy';
        this.multiDestroyUri = 'api.breeders.destroy.multi';
        this.summaryUri = 'api.breeders.summary';
        this.dataViewUri =  'api.dataview.show';

        this.appendWith = ['affiliated','location'];
        this.appendCount = ['commodities'] ;
    }

    static createForm()
    {
        return {
            user_id: null,
            fname: null,
            mname: null,
            lname: null,
            suffix: null,
            mobile_no: null,
            email: null,
            password: null,
            password_confirmation: null,
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
            fname: oldValue.fname ?? null,
            mname: oldValue.mname ?? null,
            lname: oldValue.lname ?? null,
            suffix: oldValue.suffix ?? null,
            mobile_no: oldValue.mobile_no ?? null,
            email: oldValue.email ?? null,
            //@ts-ignore
            password: oldValue.password ?? null,
            affiliation: oldValue.affiliated ? oldValue.affiliated.id : null,
            geolocation: oldValue.location ? oldValue.location.id : null,
        }
    }

    static getCardColumns() {
        return [
            {
                title: 'ID',
                key: 'id',
                align: 'center',
                visible: false,
            },
            {
                title: 'User ID',
                key: 'user_id',
                align: 'center',
                visible: false,
            },
            {
                title: 'Name',
                key: 'getFullName',
                align: 'center',
                visible: true,
            },
            {
                title: 'Affiliation',
                key: 'affiliated.name',
                align: 'center',
                visible: true,
            },
            {
                title: 'Phone',
                key: 'mobile_no',
                align: 'center',
                visible: true,
            },
            {
                title: 'Email',
                key: 'email',
                align: 'center',
                visible: true,
            },
            {
                title: 'Commodities',
                key: 'commoditiesCount',
                align: 'text-center',
                visible: true,
            },
            {
                title: 'Address',
                key: 'location.getFullAddress',
                align: 'center',
                visible: true,
            },
            {
                title: 'Updated At',
                key: 'updated_at',
                align: 'center',
                visible: false,
            },
            {
                title: 'Created At',
                key: 'created_at',
                align: 'center',
                visible: false,
            },
            {
                title: 'Deleted At',
                key: 'deleted_at',
                align: 'center',
                visible: false,
            },
        ];
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
                key: 'getFullName',
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
                // @ts-ignore
                visible: usePage().props.auth && usePage().props.auth.user ? (new User(usePage().props.auth.user)).isAdmin : true,
            },
            {
                title: 'Phone',
                key: 'mobile_no',
                db_key: 'mobile_no',
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
                db_key: 'commodities.count',
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
