import { BaseClass } from "@/Modules/core/domain/BaseClass.js";
export default class Role extends BaseClass {

        constructor(resp) {
            super(resp);
            this.id = resp.id;
            this.value = resp.value;
            this.label = resp.label;
        }

        toObject() {
            return {
                id: this.id,
                name: this.value,
                label: this.label,
            }
        }
}
