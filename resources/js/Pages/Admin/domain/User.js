import {BaseClass} from "@/Modules/core/domain/BaseClass.js";
export default class User extends BaseClass
{
    constructor(resp = {})
    {
        super();

        this.id = resp.id ?? null;
        this.fname = resp.fname ?? null;
        this.mname = resp.mname ?? null;
        this.lname = resp.lname ?? null;
        this.suffix = resp.suffix ?? null;
        this.fullName = this.getFullName();
        // count the number of accounts
        let accounts = resp.accounts ?? null;
        if(accounts)
            this.accounts = accounts.length;
        else
            this.accounts = 0;
        this.email = resp.email ?? null;
        this.affiliation = resp.affiliation ?? null;
        this.mobile_no = resp.mobile_no ?? null;
        this.email_verified_at = resp.email_verified_at ? resp.email_verified_at : "Not Verified";
    }

    getFullName() {
        return [this.fname, this.mname, this.lname, this.suffix]
            .filter(part => part)
            .join(" ");
    }

    static getHiddenColumns() {
        return ['id','updated_at', 'created_at', 'deleted_at'];
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
                title: 'Name',
                key: 'fullName',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'First Name',
                key: 'fname',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Middle Name',
                key: 'mname',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Last Name',
                key: 'lname',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Suffix',
                key: 'suffix',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'No. of Accounts',
                key: 'accounts',
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
                title: 'Affiliation',
                key: 'affiliation',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Mobile No',
                key: 'mobile_no',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Verified At',
                key: 'email_verified_at',
                align: 'center',
                sortable: true,
                visible: true,
            },
        ];
    }
}
