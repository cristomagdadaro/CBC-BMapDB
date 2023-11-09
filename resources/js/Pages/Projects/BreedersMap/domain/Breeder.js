import {BaseClass} from "@/Modules/core/domain/BaseClass.js";

export default class Breeder extends BaseClass{
    constructor(params = {}) {
        super();
        this.id = params.id ?? null;
        this.name = params.name ?? null;
        this.agency = params.agency ?? null;
        this.address = params.address ?? null;
        this.phone = params.phone ?? null;
        this.email = params.email ?? null;
    }
    static toObject(obj) {
        return Object.assign({
            id: obj.id,
            name: obj.name,
            agency: obj.agency,
            address: obj.address,
            phone: obj.phone,
            email: obj.email,
        }, obj);
    }
}
