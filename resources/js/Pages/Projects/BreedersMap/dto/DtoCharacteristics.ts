import BaseClass from "@/Modules/core/domain/base/BaseClass";
import ICharacteristics from "@/Pages/Projects/BreedersMap/interface/ICharacteristics";

export default class DtoCharacteristics extends BaseClass implements ICharacteristics {
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

    constructor(characteristics: ICharacteristics) {
        super();
        this.id = characteristics.id;
        this.weight = characteristics.weight;
        this.length = characteristics.length;
        this.width = characteristics.width;
        this.shape = characteristics.shape
        this.skin_color = characteristics.skin_color;
        this.skin_texture = characteristics.skin_texture;
        this.flesh_color = characteristics.flesh_color;
        this.flesh_texture = characteristics.flesh_texture;
        this.flesh_flavor = characteristics.flesh_flavor;
        this.aroma = characteristics.aroma;

        this.root_flesh_color = characteristics.root_flesh_color;
        this.root_cortex_color = characteristics.root_cortex_color;
        this.root_skin_color = characteristics.root_skin_color;
        this.root_shape = characteristics.root_shape;

        this.tuber_flesh_color = characteristics.tuber_flesh_color;
        this.tuber_cortex_color = characteristics.tuber_cortex_color;
        this.tuber_skin_color = characteristics.tuber_skin_color;
        this.tuber_shape = characteristics.tuber_shape;
    }
}
