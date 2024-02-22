import { BaseClass} from "@/Modules/core/domain/BaseClass.js";

export class Supplier extends BaseClass {
    constructor(params) {
        super();
        this.id = params.id;
        this.name = params.name;
        this.email = params.email;
        this.address = params.address;
        this.description = params.phone;
    }

    static toObject(supplier) {
        return {
            id: supplier.id,
            name: supplier.name,
            email: supplier.email,
            address: supplier.address,
            phone: supplier.phone
        }
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
                title: 'Email',
                key: 'email',
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
        ]
    }
}
