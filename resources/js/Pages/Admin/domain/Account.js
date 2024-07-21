import {BaseClass} from "@/Modules/core/domain/BaseClass.js";
export default class Account extends BaseClass
{
    constructor(resp = {})
    {
        super();
        this.id = resp.id ?? null;
        this.account_id = resp.account_id ?? null;
        this.user_id = resp.user_id ?? null;
        this.app_id = resp.app_id ?? null;
        this.approved_at = resp.approved_at ? resp.approved_at : "Pending Approval";
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
                title: 'Account ID',
                key: 'account_id',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Middle Name',
                key: 'user_id',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'App',
                key: 'app_id',
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
            }
        ];
    }
}
