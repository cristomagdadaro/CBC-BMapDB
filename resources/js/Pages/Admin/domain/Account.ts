import Account from "@/Modules/core/domain/auth/Account";
export default class AuthAccount extends Account
{
    constructor(account: Account)
    {
        super(account);
    }

    static getHiddenColumns() {
        return ['id','user_name','approved','app_name','updated_at', 'created_at', 'deleted_at'];
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
                title: 'User',
                key: 'user_id',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'User',
                key: 'user.getFullName',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Email',
                key: 'user.email',
                align: 'center',
                sortable: true,
                visible: true,
            },

            {
                title: 'Affiliation',
                key: 'user.affiliation',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'App',
                key: 'app_id',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Applying ',
                key: 'application.name',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Approved At',
                key: 'approved_at',
                align: 'center',
                sortable: true,
                visible: true,
            },
        ];
    }
}