import { BaseClass } from "@/Modules/core/domain/BaseClass.js";

export class Category extends BaseClass {
    constructor(params) {
        super();
        this.id = params.id;
        this.name = params.title;
        this.description = params.description;
    }

    static toObject(category) {
        return {
            id: category.id,
            name: category.name,
            description: category.description
        }
    }

    static getColumns() {
        return [
            {
                title: 'ID',
                key: 'id',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Name',
                key: 'name',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Description',
                key: 'description',
                align: 'center',
                sortable: true,
                visible: true,
            }
        ]
    }
}
