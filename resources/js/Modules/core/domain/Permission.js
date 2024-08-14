import {BaseClass} from "@/Modules/core/domain/BaseClass.js";
export default class Permission extends BaseClass {

    constructor(resp) {
        super(resp);
        this.id = resp.id;
        this.role_id = resp.role_id;
        this.label = resp.label;
        this.value = resp.value;
    }

    toObject() {
        return {
            id: this.id,
            role_id: this.role_id,
            label: this.label,
            value: this.value
        }
    }

    static get model() {
        return Permission;
    }
}
