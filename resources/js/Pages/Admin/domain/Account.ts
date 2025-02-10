import DtoAccount from "../../../Modules/core/dto/DtoAccount";

export default class AuthAccount extends DtoAccount
{
    constructor(account: DtoAccount)
    {
        super(account);

        this.indexUri = 'api.accounts.index';
        this.showUri = 'api.accounts.show';
        this.storeUri = 'api.accounts.store';
        this.updateUri = 'api.accounts.update';
        this.destroyUri = 'api.accounts.destroy';

        this.appendWith = ['user','application','role'];
        this.appendCount = ['application'];
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
            created_at: oldValue.created_at ?? null,
            user: oldValue.user ?? null,
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
                db_key: 'user.name',
                align: 'text-left',
                sortable: false,
                visible: true,
            },
            {
                title: 'Role',
                key: 'user.getRole',
                db_key: 'role.name',
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
                db_key: 'application.name',
                align: 'text-center',
                sortable: false,
                visible: true,
            },
            {
                title: 'Request Date',
                key: 'created_at',
                db_key: 'created_at',
                align: 'text-center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Approved Date',
                key: 'approved_at',
                db_key: 'approved_at',
                align: 'text-center',
                sortable: true,
                visible: true,
            },
        ];
    }
}
