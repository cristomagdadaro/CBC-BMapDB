import DtoCommodity from "../dto/DtoCommodity";

export default class Commodity extends DtoCommodity {
    constructor(params: DtoCommodity) {
        // @ts-ignore
        super(params);
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
                title: 'Breeder',
                key: 'breeder.getFullName',
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
                title: 'Commodity',
                key: 'name',
                keyLabel: 'Commodity',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Scientific Name',
                key: 'scientific_name',
                sortable: true,
                align: 'center italic',
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
                visible: true,
            },
            {
                title: 'Created At',
                key: 'created_at',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Deleted At',
                key: 'deleted_at',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Latitude',
                key: 'latitude',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Longitude',
                key: 'longitude',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Address',
                key: 'address',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'City',
                key: 'city.cityDesc',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Province',
                key: 'province',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Region',
                key: 'region',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Country',
                key: 'country',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Postal Code',
                key: 'postal_code',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Address',
                key: 'formatted_address',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Place ID',
                key: 'place_id',
                sortable: true,
                align: 'center',
                visible: true,
            },
            {
                title: 'Status',
                key: 'status',
                sortable: true,
                align: 'center',
                visible: true,
            }
        ];
    }
}
