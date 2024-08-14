import User from "@/Modules/core/domain/auth/User";
export default class AuthUser extends User
{
    constructor(user: User)
    {
        super(user);
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
                key: 'getFullName', // a get fullName() from DtoUser.ts
                align: 'left',
                sortable: false,
                visible: true,
            },
            {
                title: 'First Name',
                key: 'fname',
                align: 'left',
                sortable: true,
                visible: false,
            },
            {
                title: 'Middle Name',
                key: 'mname',
                align: 'left',
                sortable: true,
                visible: false,
            },
            {
                title: 'Last Name',
                key: 'lname',
                align: 'left',
                sortable: true,
                visible: false,
            },
            {
                title: 'Suffix',
                key: 'suffix',
                align: 'left',
                sortable: true,
                visible: false,
            },
            {
                title: 'No. of Accounts',
                key: 'accountsCount',
                align: 'center',
                sortable: false,
                visible: true,
            },
            {
                title: 'Email',
                key: 'email',
                align: 'left',
                sortable: true,
                visible: true,
            },
            {
                title: 'Affiliation',
                key: 'affiliation',
                align: 'left',
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
                align: 'left',
                sortable: true,
                visible: true,
            },
        ];
    }
}
