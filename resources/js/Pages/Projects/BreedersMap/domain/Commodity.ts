import DtoCommodity from "../dto/DtoCommodity";

export default class Commodity extends DtoCommodity {
    constructor(params: DtoCommodity) {
        // @ts-ignore
        super(params);

        this.indexUri = 'api.commodities.index';
        this.showUri = 'api.commodities.show';
        this.storeUri = 'api.commodities.store';
        this.updateUri = 'api.commodities.update';
        this.destroyUri = 'api.commodities.destroy';
        this.multiDestroyUri = 'api.commodities.destroy.multi';
        this.summaryUri = 'api.commodities.summary';

        this.appendWith = ['breeder', 'location'];
    }

    static createForm()
    {
        return {
            user_id: null,
            breeder_id: null,
            name: '',
            scientific_name: '',
            variety: '',
            accession: '',
            germplasm: '',
            population: '',
            maturity_period: '',
            yield: '',
            description: '',
            status: '',
            geolocation: '',
        };
    }

    static updateForm(oldValue: Partial<Commodity>)
    {
        return {
            id: oldValue.id ?? null,
            breeder_id: oldValue.breeder_id ?? null,
            name: oldValue.name ?? '',
            scientific_name: oldValue.scientific_name ?? '',
            variety: oldValue.variety ?? '',
            accession: oldValue.accession ?? '',
            germplasm: oldValue.germplasm ?? '',
            population: oldValue.population ?? '',
            maturity_period: oldValue.maturity_period ?? '',
            yield: oldValue.yield ?? '',
            description: oldValue.description ?? '',
            status: oldValue.status ?? '',
            geolocation: oldValue.location ? oldValue.location.id : '',
        };
    }

    static getColumns(){
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
                title: 'Breeder',
                key: 'breeder.getFullName',
                db_key: 'breeder',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Breeder ID',
                key: 'breeder_id',
                db_key: 'breeder_id',
                sortable: true,
                align: 'center',
                visible: false,
            },
            {
                title: 'Commodity',
                key: 'name',
                db_key: 'name',
                keyLabel: 'Commodity',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Scientific Name',
                key: 'scientific_name',
                db_key: 'scientific_name',
                sortable: true,
                align: 'center italic',
                visible: true,
            },
            {
                title: 'Variety',
                key: 'variety',
                db_key: 'variety',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Accession',
                key: 'accession',
                db_key: 'accession',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Germplasm',
                key: 'germplasm',
                db_key: 'germplasm',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Population',
                key: 'population',
                db_key: 'population',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Maturity Period',
                key: 'maturity_period',
                db_key: 'maturity_period',
                sortable: false,
                align: 'center',
                visible: true,
            },
            {
                title: 'Yield',
                key: 'yield',
                db_key: 'yield',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Description',
                key: 'description',
                db_key: 'description',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Status',
                key: 'status',
                db_key: 'status',
                sortable: true,
                align: 'center',
                visible: false,
            },
            {
                title: 'Location',
                key: 'location.getFullAddress',
                db_key: 'location',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Updated At',
                key: 'updated_at',
                db_key: 'updated_at',
                sortable: true,
                align: 'center',
                visible: false,
            },
            {
                title: 'Created At',
                key: 'created_at',
                db_key: 'created_at',
                sortable: true,
                align: 'center',
                visible: false,
            },
            {
                title: 'Deleted At',
                key: 'deleted_at',
                db_key: 'deleted_at',
                sortable: true,
                align: 'center',
                visible: false,
            }
        ];
    }
}
