import IBreeder from "./IBreeder";

export default interface ICommodity {
    id: number;
    name: string;
    breeder_id: number;
    scientific_name: string;
    variety: string;
    accession: string;
    germplasm: string;
    population: string;
    maturity_period: string;
    yield: string;
    description: string;
    latitude: string;
    longitude: string;
    address: string;
    city: string;
    province: string;
    region: string;
    country: string;
    postal_code: string;
    formatted_address: string;
    place_id: string;
    status: string;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    breeder: IBreeder;
}
