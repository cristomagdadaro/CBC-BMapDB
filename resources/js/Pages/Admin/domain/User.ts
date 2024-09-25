import User from "@/Modules/core/domain/auth/User";
export default class AuthUser extends User
{
    accounts_count: number;

    constructor(user: User)
    {
        super(user);
        //@ts-ignore
        this.accounts_count = user.accounts_count;
    }

    static createForm()
    {
        return {
            fname: null,
            mname: null,
            lname: null,
            suffix: null,
            mobile_no: null,
            email: null,
            affiliation: null,
            account_for: null,
            password: null,
            password_confirmation: null,
            terms: null,
        }
    }

    static updateForm(oldValue: Partial<AuthUser>)
    {
        return {
            id: oldValue.id ?? null,
            fname: oldValue.fname ?? null,
            mname: oldValue.mname ?? null,
            lname: oldValue.lname ?? null,
            suffix: oldValue.suffix ?? null,
            mobile_no: oldValue.mobile_no ?? null,
            email: oldValue.email ?? null,
            affiliation: oldValue.affiliated ? oldValue.affiliated.id : null,
            // @ts-ignore
            account_for: oldValue.account_for ? oldValue.account_for.id : null,
            password: null,
            password_confirmation: null,
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
                title: 'Name',
                key: 'getFullName', // a get fullName() from DtoUser.ts
                db_key: 'name',
                align: 'left',
                sortable: false,
                visible: true,
            },
            {
                title: 'First Name',
                key: 'fname',
                db_key: 'fname',
                align: 'left',
                sortable: true,
                visible: false,
            },
            {
                title: 'Middle Name',
                key: 'mname',
                db_key:'mname',
                align: 'left',
                sortable: true,
                visible: false,
            },
            {
                title: 'Last Name',
                key: 'lname',
                db_key: 'lname',
                align: 'left',
                sortable: true,
                visible: false,
            },
            {
                title: 'Suffix',
                key: 'suffix',
                db_key:'suffix',
                align: 'left',
                sortable: true,
                visible: false,
            },
            {
                title: 'Role',
                key: 'getRole',
                db_key: null,
                align: 'left',
                sortable: true,
                visible: true,
            },
            {
                title: 'No. of Accounts',
                key: 'accounts_count',
                db_key: 'accounts_count',
                align: 'text-center',
                sortable: false,
                visible: true,
            },
            {
                title: 'Email',
                key: 'email',
                db_key: 'email',
                align: 'left',
                sortable: true,
                visible: true,
            },
            {
                title: 'Affiliation',
                key: 'affiliated.name',
                db_key: 'affiliated',
                align: 'left',
                sortable: true,
                visible: true,
            },
            {
                title: 'Mobile No',
                key: 'mobile_no',
                db_key: 'mobile_no',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Verified At',
                key: 'email_verified_at',
                db_key: 'email_verified_at',
                align: 'left',
                sortable: true,
                visible: true,
            },
        ];
    }
}
