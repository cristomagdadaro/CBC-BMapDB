import BaseClass from "@/Modules/core/domain/base/BaseClass";

export default class CommodityRequest extends BaseClass {
    constructor(params = {}) {
        super(params);
    }

    static getColumns() {
        return [
            { db_key: 'id', title: 'ID', visible: true, align: 'text-left' },
            { db_key: 'name', title: 'Common Name', visible: true, align: 'text-left' },
            { db_key: 'scientific_name', title: 'Scientific Name', visible: true, align: 'text-left' },
            { db_key: 'status', title: 'Status', visible: true, align: 'text-center' },
            { db_key: 'created_at', title: 'Date Requested', visible: true, align: 'text-left' }
        ];
    }
}
