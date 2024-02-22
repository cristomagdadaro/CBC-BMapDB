import {BaseClass} from "@/Modules/core/domain/BaseClass.js";

export default class Breeder extends BaseClass{
    constructor(params = {}) {
        super();
        this.id = params.id ?? null;
        this.name = params.name ?? null;
        this.agency = params.agency ?? null;
        this.address = params.address ?? null;
        this.phone = params.phone ?? null;
        this.email = params.email ?? null;
    }
    static toObject(obj) {
        return Object.assign({
            id: obj.id,
            name: obj.name,
            agency: obj.agency,
            address: obj.address,
            phone: obj.phone,
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
                title: 'Agency',
                key: 'agency',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Address',
                key: 'address',
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
        ];
    }
}
