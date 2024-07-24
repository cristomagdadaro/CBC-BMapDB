import {BaseClass} from "@/Modules/core/domain/BaseClass.js";
import User from "@/Pages/Admin/domain/User.js";
import Application from "@/Pages/Admin/domain/Application.js";
export default class Account extends BaseClass
{
    constructor(resp = {})
    {
        super();
        this.id = resp.id ?? null;
        this.user_id = resp.user_id ?? null;
        this.user_name = resp.user ? (new User(resp.user)).getFullName() : null
        this.app_id = resp.app_id ?? null;
        this.app_name = resp.application ? (new Application(resp.application)).name : null;
        this.approved_at = resp.approved_at ?? null;
        this.approved = resp.approved_at ? 'Approved': 'Pending Approval';

        this.application = new Application(resp.application);

        console.log(resp);
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
                key: 'user_name',
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
                title: 'App',
                key: 'app_name',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Approved At',
                key: 'approved_at',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Approved',
                key: 'approved',
                align: 'center',
                sortable: true,
                visible: true,
            }
        ];
    }
}
