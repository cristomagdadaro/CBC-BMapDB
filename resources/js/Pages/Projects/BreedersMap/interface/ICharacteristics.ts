export default interface ICharacteristics {
    id?: number;
    weight?: number; // Weight in grams
    length?: number; // Length in cm
    width?: number; // Width in cm
    shape?: string; // General shape
    skin_color?: string; // Skin or peel color
    skin_texture?: string; // Skin or peel texture
    flesh_color?: string; // Flesh color
    flesh_texture?: string; // Flesh texture
    flesh_flavor?: string; // Flesh flavor
    aroma?: string; // Aroma

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
}
