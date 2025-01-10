import Account from "@/Modules/core/domain/auth/Account";
export default class AuthAccount extends Account
{
    constructor(account: Account)
    {
        super(account);
    }

    static createForm()
    {
        return {
            user_id: null,
            app_id: null,
        }
    }

    static updateForm(oldValue: Partial<AuthAccount>)
    {
        return {
            id: oldValue.id ?? null,
            user_id: oldValue.user_id ?? null,
            app_id: oldValue.app_id ?? null,
            approved_at: oldValue.approved_at ?? null,
            // @ts-ignore
            permissions: oldValue.permissions ?? [],
            // @ts-ignore
            role: oldValue.role ?? null,

        }
    }

    static getColumns() {
        return [
            {
                title: 'Accnt. ID',
                key: 'id',
                db_key: 'id',
                align: 'text-center',
                sortable: true,
                visible: false,
            },
            {
                title: 'User ID',
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
