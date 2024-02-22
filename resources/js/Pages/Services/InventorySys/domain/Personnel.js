import { BaseClass} from "@/Modules/core/domain/BaseClass.js";

export class Personnel extends BaseClass {
    constructor(params) {
        super();
        this.id = params.id;
        this.fname = params.fname;
        this.mname = params.mname;
        this.lname = params.lname;
        this.suffix = params.suffix;
        this.position = params.position;
        this.phone = params.phone;
        this.address = params.address;
        this.email = params.email;
    }

    static toObject(personnel) {
        return {
            id: personnel.id,
            fname: personnel.fname,
            mname: personnel.mname,
            lname: personnel.lname,
            suffix: personnel.suffix,
            position: personnel.position,
            phone: personnel.phone,
            address: personnel.address,
            email: personnel.email
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
                title: 'First Name',
                key: 'fname',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Middle Name',
                key: 'mname',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Last Name',
                key: 'lname',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Suffix',
                key: 'suffix',
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
                title: 'Phone',
                key: 'phone',
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
                title: 'Email',
                key: 'email',
                align: 'center',
                sortable: true,
                visible: true,
            }
        ]
    }
}
