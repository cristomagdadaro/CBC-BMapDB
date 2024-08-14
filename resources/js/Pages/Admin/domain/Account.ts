import Account from "@/Modules/core/domain/auth/Account";
export default class AuthAccount extends Account
{
    constructor(account: Account)
    {
        super(account);
    }

    static getHiddenColumns() {
        return ['id','table','user_id','app_id','updated_at', 'created_at', 'deleted_at'];
    }

    static getColumns() {
        return [
            {
                title: 'Accnt.ID',
                key: 'id',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'UID',
                key: 'user_id',
                align: 'center',
                sortable: true,
                visible: true
                ,
            },
            {
                title: 'User',
                key: 'user.getFullName',
                align: 'center',
                sortable: false,
                visible: true,
            },
            {
                title: 'Role',
                key: 'user.getRole',
                align: 'center',
                sortable: false,
                visible: true,
            },
            {
                title: 'Email',
                key: 'user.email',
                align: 'center',
                sortable: false,
                visible: true,
            },

            {
                title: 'Affiliation',
                key: 'user.affiliation',
                align: 'center',
                sortable: false,
                visible: true,
            },
            {
                title: 'App',
                key: 'app_id',
                align: 'center',
                sortable: false,
                visible: false,
            },
            {
                title: 'Applying ',
                key: 'application.name',
                align: 'center',
                sortable: false,
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
