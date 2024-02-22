import { BaseClass} from "@/Modules/core/domain/BaseClass.js";

export class Item extends BaseClass {
    constructor(params) {
        super();
        this.id = params.id;
        this.name = params.name;
        this.brand = params.brand;
        this.description = params.description;
        this.category_id = params.category_id;
        //this.image = params.image;
    }

    static toObject(item) {
        return {
            id: item.id,
            name: item.name,
            brand: item.brand,
            description: item.description,
            category_id: item.category_id,
            image: item.image
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
                title: 'Brand',
                key: 'brand',
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
            },
            {
                title: 'Category',
                key: 'category_id',
                align: 'center',
                sortable: true,
                visible: true,
            },
            /*{
                title: 'Image',
                key: 'image',
                align: 'center',
                sortable: true,
                visible: true,
            },*/
        ];
    }
}
