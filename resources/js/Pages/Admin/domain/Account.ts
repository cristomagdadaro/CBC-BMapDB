import Account from "@/Modules/core/domain/auth/Account";
export default class AuthAccount extends Account
{
    constructor(account: Account)
    {
        super(account);
    }

    static getColumns() {
        return [
            {
                title: 'Accnt. ID',
                key: 'id',
                db_key: 'id',
                align: 'text-center',
                sortable: true,
                visible: true,
            },
            {
                title: 'U. ID',
                key: 'user_id',
                db_key: 'user_id',
                align: 'text-center',
                sortable: true,
                visible: true
                ,
            },
            {
                title: 'User',
                key: 'user.getFullName',
                db_key: 'name',
                align: 'text-left',
                sortable: false,
                visible: true,
            },
            {
                title: 'Role',
                key: 'user.getRole',
                db_key: 'name',
                align: 'text-left',
                sortable: false,
                visible: true,
            },
            {
                title: 'Email',
                key: 'user.email',
                db_key: 'email',
                align: 'text-left',
                sortable: false,
                visible: true,
            },

            {
                title: 'Affiliation',
                key: 'user.affiliation',
                db_key: 'affiliation',
                align: 'text-left',
                sortable: false,
                visible: true,
            },
            {
                title: 'App',
                key: 'app_id',
                db_key: 'app_id',
                align: 'text-center',
                sortable: false,
                visible: false,
            },
            {
                title: 'Database App',
                key: 'application.name',
                db_key: 'name',
                align: 'text-center',
                sortable: false,
                visible: true,
            },
            {
                title: 'Approved At',
                key: 'approved_at',
                db_key: 'approved_at',
                align: 'text-center',
                sortable: true,
                visible: true,
            },
        ];
    }
}
