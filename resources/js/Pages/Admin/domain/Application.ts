import Application from "@/Modules/core/domain/auth/Application";
export default class AuthApplication extends Application
{
    constructor(app: Application)
    {
        super(app);
    }

    static createForm()
    {
        return {
            name: null,
            description: null,
            url: null,
            icon: null,
            status: null,
        }
    }

    static updateForm(oldValue: Partial<AuthApplication>) {
        return {
            id: oldValue.id ?? null,
            name: oldValue.name ?? null,
            description: oldValue.description ?? null,
            url: oldValue.url ?? null,
            icon: oldValue.icon ?? null,
            status: oldValue.status ?? null,
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
                title: 'App',
                key: 'name',
                db_key: 'name',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Description',
                key: 'description',
                db_key: 'description',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'URL',
                key: 'url',
                db_key: 'url',
                align: 'left',
                sortable: true,
                visible: true,
            },
            {
                title: 'Icon',
                key: 'icon',
                db_key: 'icon',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Status',
                key: 'status',
                db_key: 'status',
                align: 'center',
                sortable: true,
                visible: true,
            },
        ];
    }
}
