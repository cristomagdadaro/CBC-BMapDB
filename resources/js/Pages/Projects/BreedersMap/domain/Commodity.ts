import DtoCommodity from "../dto/DtoCommodity";

export default class Commodity extends DtoCommodity {
    constructor(params: DtoCommodity) {
        super(params);
    }

    static getHiddenColumns() {
        return ['id','breeder_id', 'latitude', 'longitude','updated_at', 'created_at', 'deleted_at','place_id'];
    }

    static getColumns(){
        return [
            {
                title: 'ID',
                key: 'id',
                sortable: true,
                align: 'center',
                visible: false,
            },
            {
                title: 'Commodity',
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
                visible: false,
            },
            {
                title: 'Breeder',
                key: 'breeder.getFullName',
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
                visible: false,
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
            {
                title: 'Latitude',
                key: 'latitude',
                sortable: true,
                align: 'center',
                visible: false,
            },
            {
                title: 'Longitude',
                key: 'longitude',
                sortable: true,
                align: 'center',
                visible: false,
            },
            {
                title: 'Address',
                key: 'address',
                sortable: true,
                align: 'center',
                visible: false,
            },
            {
                title: 'City',
                key: 'city',
                sortable: true,
                align: 'center',
                visible: false,
            },
            {
                title: 'Province',
                key: 'province',
                sortable: true,
                align: 'center',
                visible: false,
            },
            {
                title: 'Region',
                key: 'region',
                sortable: true,
                align: 'center',
                visible: false,
            },
            {
                title: 'Country',
                key: 'country',
                sortable: true,
                align: 'center',
                visible: false,
            },
            {
                title: 'Postal Code',
                key: 'postal_code',
                sortable: true,
                align: 'center',
                visible: false,
            },
            {
                title: 'Address',
                key: 'formatted_address',
                sortable: true,
                align: 'center',
                visible: false,
            },
            {
                title: 'Place ID',
                key: 'place_id',
                sortable: true,
                align: 'center',
                visible: false,
            },
            {
                title: 'Status',
                key: 'status',
                sortable: true,
                align: 'center',
                visible: false,
            }
        ];
    }
}