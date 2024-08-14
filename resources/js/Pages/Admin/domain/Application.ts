import Application from "@/Modules/core/domain/auth/Application";
export default class AuthApplication extends Application
{
    constructor(app: Application)
    {
        super(app);
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
                title: 'App',
                key: 'name',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Description',
                key: 'description',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'URL',
                key: 'url',
                align: 'left',
                sortable: true,
                visible: true,
            },
            {
                title: 'Icon',
                key: 'icon',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Status',
                key: 'status',
                align: 'center',
                sortable: true,
                visible: true,
            },
        ];
    }
}
