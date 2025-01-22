import DtoCharacteristics from "@/Pages/Projects/BreedersMap/dto/DtoCharacteristics";

export default class Characteristics extends DtoCharacteristics {
    id?: number;
    weight?: number;
    length?: number;
    width?: number;
    shape?: string;
    skin_color?: string;
    skin_texture?: string;
    flesh_color?: string;
    flesh_texture?: string;
    flesh_flavor?: string;
    aroma?: string;

    // Root characteristics
    root_flesh_color?: string;
    root_cortex_color?: string;
    root_skin_color?: string;
    root_shape?: string;

    // Tuber characteristics
    tuber_flesh_color?: string;
    tuber_cortex_color?: string;
    tuber_skin_color?: string;
    tuber_shape?: string;

    constructor(characteristics: DtoCharacteristics) {
        super(characteristics);
    }

    static getColumns() {
        return [
            {
                title: 'ID',
                key: 'id',
                db_key: 'id',
                sortable: true,
                align: 'center',
                visible: false,
            },
            {
                title: 'Weight',
                key: 'weight',
                db_key: 'weight',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Length',
                key: 'length',
                db_key: 'length',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Width',
                key: 'width',
                db_key: 'width',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Skin Color',
                key: 'skin_color',
                db_key: 'skin_color',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Skin Texture',
                key: 'skin_texture',
                db_key: 'skin_texture',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Flesh Color',
                key: 'flesh_color',
                db_key: 'flesh_color',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Flesh Texture',
                key: 'flesh_texture',
                db_key: 'flesh_texture',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Flesh Flavor',
                key: 'flesh_flavor',
                db_key: 'flesh_flavor',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Aroma',
                key: 'aroma',
                db_key: 'aroma',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Root Flesh Color',
                key: 'root_flesh_color',
                db_key: 'root_flesh_color',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Root Cortex Color',
                key: 'root_cortex_color',
                db_key: 'root_cortex_color',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Root Skin Color',
                key: 'root_skin_color',
                db_key: 'root_skin_color',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Root Shape',
                key: 'root_shape',
                db_key: 'root_shape',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Tuber Flesh Color',
                key: 'tuber_flesh_color',
                db_key: 'tuber_flesh_color',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Tuber Cortex Color',
                key: 'tuber_cortex_color',
                db_key: 'tuber_cortex_color',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Tuber Skin Color',
                key: 'tuber_skin_color',
                db_key: 'tuber_skin_color',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Tuber Shape',
                key: 'tuber_shape',
                db_key: 'tuber_shape',
                sortable: true,
                align: 'center',
                visible: true,
            }
        ]
    }
}
