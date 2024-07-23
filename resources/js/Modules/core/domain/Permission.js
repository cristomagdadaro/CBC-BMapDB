import {BaseClass} from "@/Modules/core/domain/BaseClass.js";
export default class Permission extends BaseClass {

    constructor(resp) {
        super(resp);
        this.id = resp.id;
        this.name = resp.name;
        this.guard_name = resp.guard_name;
    }

    toObject() {
        return {
            id: this.id,
            name: this.name,
            guard_name: this.guard_name
        }
    }

    static get model() {
        return Permission;
    }
}
