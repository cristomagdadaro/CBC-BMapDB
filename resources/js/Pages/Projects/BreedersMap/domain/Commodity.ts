import DtoCommodity from "../dto/DtoCommodity";

export default class Commodity extends DtoCommodity {
    constructor(params: DtoCommodity) {
        // @ts-ignore
        super(params);

        this.appendWith = ['breeder', 'location', 'characteristics', 'additionalinfo'];
    }

    static importTemplateHeaders() {
        const exclude = ['user_id','photo'];
        return Object.keys(this.createForm()).filter(k => !exclude.includes(k));
    }

    static getCreateFieldTitles() {
        return {
            user_id: 'Enter the name of the data encoder',
            breeder_id: 'Select the plant breeder',
            name: 'Select a commodity name',
            scientific_name: 'Enter the scientific name (if applicable)',
            accession: 'Enter the accession number',
            yield: 'Specify the expected yield (e.g., tons per hectare)',
            description: 'Provide additional details or remarks',
            photo: '5mb max file size',
            geolocation: 'Enter the location coordinates or region',

            weight: 'Enter the average weight (in grams or kilograms)',
            length: 'Specify the length (in cm)',
            width: 'Specify the width (in cm)',
            shape: 'Describe the shape',

            skin_color: 'Describe the skin color',
            skin_texture: 'Describe the skin texture',
            flesh_color: 'Specify the flesh color',
            flesh_texture: 'Describe the flesh texture',
            flesh_flavor: 'Describe the flavor profile',
            aroma: 'Describe the aroma (if applicable)',

            root_flesh_color: 'Specify the root flesh color',
            root_cortex_color: 'Specify the root cortex color',
            root_skin_color: 'Specify the root skin color',
            root_shape: 'Describe the root shape',

            tuber_flesh_color: 'Specify the tuber flesh color',
            tuber_cortex_color: 'Specify the tuber cortex color',
            tuber_skin_color: 'Specify the tuber skin color',
            tuber_shape: 'Describe the tuber shape',

            regulations: 'Enter the regulations or approvals related to this commodity',
        };
    }

    static getUpdateFieldTitles() {
        return this.getCreateFieldTitles();
    }

    static createForm()
    {
        return {
            user_id: null,
            breeder_id: null,
            name: '',
            scientific_name: '',
            accession: '',
            yield: '',
            description: '',
            photo: '',
            geolocation: '',

            weight: '',
            length: '',
            width: '',
            shape: '',

            skin_color: '',
            skin_texture: '',
            flesh_color: '',
            flesh_texture: '',
            flesh_flavor: '',
            aroma: '',

            root_flesh_color: '',
            root_cortex_color: '',
            root_skin_color: '',
            root_shape: '',

            tuber_flesh_color: '',
            tuber_cortex_color: '',
            tuber_skin_color: '',
            tuber_shape: '',

            regulations: '',
            stress_resilience: '',
        };
    }

    static updateForm(oldValue: Partial<Commodity>)
    {
        return {
            id: oldValue.id ?? null,
            breeder_id: oldValue.breeder_id ?? null,
            name: oldValue.name ?? '',
            scientific_name: oldValue.scientific_name ?? '',
            accession: oldValue.accession ?? '',
            yield: oldValue.yield ?? '',
            description: oldValue.description ?? '',
            photo: oldValue.photo ?? '',
            geolocation: oldValue.location ? oldValue.location.id : '',

            weight: oldValue.characteristics?.weight ?? '',
            length: oldValue.characteristics?.length ?? '',
            width: oldValue.characteristics?.width ?? '',
            shape: oldValue.characteristics?.shape ?? '',

            skin_color: oldValue.characteristics?.skin_color ?? '',
            skin_texture: oldValue.characteristics?.skin_texture ?? '',
            flesh_color: oldValue.characteristics?.flesh_color ?? '',
            flesh_texture: oldValue.characteristics?.flesh_texture ?? '',
            flesh_flavor: oldValue.characteristics?.flesh_flavor ?? '',
            aroma: oldValue.characteristics?.aroma ?? '',

            root_flesh_color: oldValue.characteristics?.root_flesh_color ?? '',
            root_cortex_color: oldValue.characteristics?.root_cortex_color ?? '',
            root_skin_color: oldValue.characteristics?.root_skin_color ?? '',
            root_shape: oldValue.characteristics?.root_shape ?? '',
            tuber_flesh_color: oldValue.characteristics?.tuber_flesh_color ?? '',
            tuber_cortex_color: oldValue.characteristics?.tuber_cortex_color ?? '',
            tuber_skin_color: oldValue.characteristics?.tuber_skin_color ?? '',
            tuber_shape: oldValue.characteristics?.tuber_shape ?? '',

            regulations: oldValue.regulations ?? null,
            stress_resilience: oldValue.stress_resilience ?? null,
        };
    }

    static getCardColumns() {
        return [
            {
                title: 'ID',
                key: 'id',
                db_key: 'id',
                align: 'center',
                visible: false,
            },
            {
                title: 'User ID',
                key: 'user_id',
                db_key: 'user_id',
                align: 'center',
                visible: false,
            },
            {
                title: 'Breeder',
                key: 'breederName',
                db_key: 'breeder',
                align: 'center',
                class: 'whitespace-nowrap',
                visible: true,
            },
            {
                title: 'Commodity',
                key: 'name',
                db_key: 'name',
                align: 'center',
                visible: true,
            },
            {
                title: 'Scientific Name',
                key: 'scientific_name',
                db_key: 'scientific_name',
                align: 'center italic',
                class: 'italic',
                visible: true,
            },
            {
                title: 'Breeder ID',
                key: 'breeder_id',
                db_key: 'breeder_id',
                align: 'center',
                visible: false,
            },
            {
                title: 'Type',
                key: 'type',
                db_key: 'type',
                align: 'center',
                visible: true,
            },
            {
                title: 'Institution/Agency',
                key: 'breederAffiliation',
                db_key: 'affiliation',
                align: 'center',
                visible: true,
            },
            {
                title: 'Email',
                key: 'breederEmail',
                db_key: 'breeder.email',
                align: 'center',
                visible: false,
            },
            {
                title: 'Contact #',
                key: 'breederMobileNo',
                db_key: 'breeder.mobile_no',
                align: 'center',
                visible: true,
            },
            {
                title: 'Accession No.',
                key: 'accession',
                db_key: 'accession',
                align: 'center',
                visible: false,
            },
            {
                title: 'Yield',
                key: 'yield',
                db_key: 'yield',
                align: 'center',
                visible: false,
            },
            {
                title: 'Description',
                key: 'description',
                db_key: 'description',
                align: 'center',
                visible: false,
            },
            {
                title: 'Location',
                key: 'location.getFullAddress',
                db_key: 'geo_location',
                align: 'center',
                visible: true,
            },
            {
                title: 'Updated At',
                key: 'updated_at',
                db_key: 'updated_at',
                align: 'center',
                visible: false,
            },
            {
                title: 'Created At',
                key: 'created_at',
                db_key: 'created_at',
                align: 'center',
                visible: false,
            },
            {
                title: 'Deleted At',
                key: 'deleted_at',
                db_key: 'deleted_at',
                align: 'center',
                visible: false,
            },
            {
                title: 'Characteristics',
                key: 'characteristics',
                db_key: 'characteristics',
                align: 'center',
                visible: false,
            },
            {
                title: 'Regulatory Compliance',
                key: 'regulations',
                db_key: 'regulations',
                align: 'center',
                visible: false,
            }
        ]
    }

    static getColumns(){
        return [
            {
                title: 'ID',
                key: 'id',
                db_key: 'id',
                sortable: false,
                align: 'center',
                visible: false,
            },
            {
                title: 'User ID',
                key: 'user_id',
                db_key: 'user_id',
                align: 'center',
                sortable: false,
                visible: false,
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
                title: 'Accession No.',
                key: 'accession',
                db_key: 'accession',
                sortable: true,
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
                visible: false,
            },
            {
                title: 'Breeder',
                key: 'breeder.getFullName',
                db_key: 'breeder.name',
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
                title: 'Approved',
                key: 'approved_at',
                db_key: 'approved_at',
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
            },
            {
                title: 'Characteristics',
                key: 'characteristics',
                db_key: 'characteristics',
                sortable: true,
                align: 'center',
                visible: false,
            },
            {
                title: 'Regulatory Compliance',
                key: 'regulations',
                db_key: 'regulations',
                sortable: true,
                align: 'center',
                visible: false,
            },
        ];
    }
}
