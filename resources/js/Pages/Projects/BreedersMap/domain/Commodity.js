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
        this.updated_at = params.updated_at ?? null;
        this.created_at = params.created_at ?? null;
        this.deleted_at = params.deleted_at ?? null;
    }

    static getHiddenColumns() {
        return ['updated_at', 'created_at', 'deleted_at'];
    }

    static getColumns(){
        return [
            {
                title: 'ID',
                key: 'id',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Name',
                key: 'name',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Breeder ID',
                key: 'breeder_id',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Scientific Name',
                key: 'scientific_name',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Variety',
                key: 'variety',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Accession',
                key: 'accession',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Germplasm',
                key: 'germplasm',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Population',
                key: 'population',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Maturity Period',
                key: 'maturity_period',
                sortable: false,
                align: 'center',
                visible: true,
            },
            {
                title: 'Yield',
                key: 'yield',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Description',
                key: 'description',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Updated At',
                key: 'updated_at',
                sortable: true,
                align: 'center',
                visible: false,
            },
            {
                title: 'Created At',
                key: 'created_at',
                sortable: true,
                align: 'center',
                visible: false,
            },
            {
                title: 'Deleted At',
                key: 'deleted_at',
                sortable: true,
                align: 'center',
                visible: false,
            },
        ];
    }
}
