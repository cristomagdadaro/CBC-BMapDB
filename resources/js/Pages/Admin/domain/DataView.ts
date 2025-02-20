import DtoDataView from "@/Modules/core/dto/DtoDataView";

export default class DataView extends DtoDataView {
    constructor(dto: DtoDataView) {
        super(dto);

        this.indexUri = 'api.dataview.index';
        this.showUri = 'api.dataview.show';
       /* this.storeUri = 'api.dataview.store';
        this.updateUri = 'api.dataview.update';
        this.destroyUri = 'api.dataview.destroy';*/
    }

    static createForm() {
        return {
            user_account_id: null,
            model: null,
            columns: null,
            visibility_guard: null,
        }
    }

    static updateForm(oldValue: Partial<DtoDataView>) {
        return {
            uuid: oldValue.uuid ?? null,
            user_account_id: oldValue.user_account_id ?? null,
            model: oldValue.model ?? null,
            columns: oldValue.columns ?? [],
            visibility_guard: oldValue.visibility_guard ?? null,
        }
    }

    static getColumns() {
        return [
            {
                title: 'User Accnt. ID',
                key: 'user_account_id',
                db_key: 'user_account_id',
                align: 'text-center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Table',
                key: 'model',
                db_key: 'model',
                align: 'text-center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Visibility Guard',
                key: 'visibility_guard',
                db_key: 'visibility_guard',
                align: 'text-center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Columns',
                key: 'columns',
                db_key: 'columns',
                align: 'text-left',
                sortable: true,
                visible: true,
            },
        ];
    }
}
