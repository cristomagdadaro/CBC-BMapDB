import BaseClass from "../domain/base/BaseClass";
import IDataView from "@/Modules/core/interface/base/IDataView";

export default class DtoDataView extends BaseClass implements IDataView {
    uuid: string = null;
    user_account_id: string = null;
    model: string = null;
    columns: Array<string> = [];
    visibility_guard: string = null;
    default: Array<string> = [];

    constructor(dto: IDataView) {
        super();

        this.table = 'data_views';

        this.uuid = dto.uuid;
        this.user_account_id = dto.user_account_id;
        this.model = dto.model;
        this.columns = dto.columns;
        this.visibility_guard = dto.visibility_guard;
        this.default = dto.default;
    }

    get getVisibilityGuard() {
        return this.visibility_guard;
    }

    get getVisibleColumns(){
        return this.columns;
    }

    get getDefaultColumns() {
        return this.default;
    }
}
