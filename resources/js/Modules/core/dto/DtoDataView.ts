import BaseClass from "../domain/base/BaseClass";
import IDataView from "@/Modules/core/interface/base/IDataView";

export default class DtoDataView extends BaseClass implements IDataView {
    user_account_id: string = null;
    model: string = null;
    columns: string = null;
    visibility_guard: string = null;

    constructor(dto: IDataView) {
        super();

        this.table = 'data_views';

        this.user_account_id = dto.user_account_id;
        this.model = dto.model;
        this.columns = dto.columns;
        this.visibility_guard = dto.visibility_guard;
    }

    get getVisibilityGuard() {
        return this.visibility_guard;
    }

    get getColumns(){
        return this.columns;
    }
}
