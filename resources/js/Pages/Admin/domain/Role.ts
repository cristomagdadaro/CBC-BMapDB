import DtoRole from "../../../Modules/core/dto/DtoRole";

export default class Role extends DtoRole {
    constructor(role: DtoRole)
    {
        super(role);

        this.appendWith = ['permissions'];
    }

    static createForm()
    {
        return {
            name: null,
            guard_name: null,
            permissions: null,
        }
    }

    static updateForm(oldValue: Partial<DtoRole>)
    {
        return {
            id: oldValue.id ?? null,
            name: oldValue.name ?? null,
            guard_name: oldValue.guard_name ?? null,
            permissions: oldValue.permissions ?? null,
        };
    }

    static getColumns() {
        return [
            {
                title: 'ID',
                key: 'id',
                db_key: 'id',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Name',
                key: 'name',
                db_key: 'name',
                align: 'left',
                sortable: false,
                visible: true,
            },
            {
                title: 'Guard',
                key: 'guard_name',
                db_key: 'guard_name',
                align: 'left',
                sortable: true,
                visible: true,
            },
            {
                title: 'Permissions',
                key: 'getPermissions',
                db_key:'permissions.name',
                align: 'left',
                sortable: true,
                visible: true,
            },
        ];
    }
}
