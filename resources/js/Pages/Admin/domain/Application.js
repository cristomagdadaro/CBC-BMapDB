import {BaseClass} from "@/Modules/core/domain/BaseClass.js";
export default class Application extends BaseClass
{
    constructor(resp = {})
    {
        super();
        this.id = resp.id ?? null;
        this.name = resp.name ?? null;
        this.description = resp.description ?? null;
        this.url = resp.url ?? null;
        this.icon = resp.icon ?? null;
        this.status = resp.status ? "Active" : "Inactive";
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
                align: 'center',
                sortable: true,
                visible: false,
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
