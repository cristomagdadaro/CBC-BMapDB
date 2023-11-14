import {BaseClass} from "@/Modules/core/domain/BaseClass.js";

export default class Commodity extends BaseClass{
    constructor(params = {}) {
        super();
        this.id = params.id ?? null;
        this.name = params.name ?? null;
        this.breeder_id = params.breeder_id ?? null;
        this.scientific_name = params.scientific_name ?? null;
        this.variety = params.variety ?? null;
        this.accession = params.accession ?? null;
        this.germplasm = params.germplasm ?? null;
        this.population = params.population ?? null;
        this.maturity_period = params.maturity_period ?? null;
        this.yield = params.yield ?? null;
        this.description = params.description ?? null;
    }

    static toObject(obj) {
        return Object.assign({
            id: obj.id,
            name: obj.name,
            breeder_id: obj.breeder_id,
            scientific_name: obj.scientific_name,
            variety: obj.variety,
            accession: obj.accession,
            germplasm: obj.germplasm,
            population: obj.population,
            maturity_period: obj.maturity_period,
            yield: obj.yield,
            description: obj.description,
        }, obj);
    }

    static getColumns(){
        return [
            {
                title: 'ID',
                key: 'id',
                sortable: true,
                align: 'center',
            },
            {
                title: 'Name',
                key: 'name',
                sortable: true,
                align: 'center',
            },
            {
                title: 'Breeder ID',
                key: 'breeder_id',
                sortable: true,
                align: 'center',
            },
            {
                title: 'Scientific Name',
                key: 'scientific_name',
                sortable: true,
                align: 'center',
            },
            {
                title: 'Variety',
                key: 'variety',
                sortable: true,
                align: 'center',
            },
            {
                title: 'Accession',
                key: 'accession',
                sortable: true,
                align: 'center',
            },
            {
                title: 'Germplasm',
                key: 'germplasm',
                sortable: true,
                align: 'center',
            },
            {
                title: 'Population',
                key: 'population',
                sortable: true,
                align: 'center',
            },
            {
                title: 'Maturity Period',
                key: 'maturity_period',
                sortable: false,
                align: 'center',
            },
            {
                title: 'Yield',
                key: 'yield',
                sortable: true,
                align: 'center',
            },
            {
                title: 'Description',
                key: 'description',
                sortable: true,
                align: 'center',
            },
        ];
    }
}
