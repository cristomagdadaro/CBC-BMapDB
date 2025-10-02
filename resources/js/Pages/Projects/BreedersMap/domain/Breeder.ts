import DtoBreeder from "../dto/DtoBreeder";

export default class Breeder extends DtoBreeder{
    constructor(params : DtoBreeder) {
        super(params);
        this.indexUri = 'api.breeders.index';
        this.showUri = 'api.breeders.show';
        this.storeUri = 'api.breeders.store';
        this.updateUri = 'api.breeders.update';
        this.destroyUri = 'api.breeders.destroy';
        this.multiDestroyUri = 'api.breeders.destroy.multi';
        this.summaryUri = 'api.breeders.summary';
        this.dataViewUri =  'api.dataview.show';

        this.appendWith = ['affiliated','location','commodities'];
        this.appendCount = ['commodities'] ;
    }

    static createForm()
    {
        return {
            user_id: null,
            fname: null,
            mname: null,
            lname: null,
            suffix: null,
            mobile_no: null,
            email: null,
            password: null,
            password_confirmation: null,
            affiliation: null,
            position: null,
            educ_level: null,
            expertise: null,
            research_interest: null,
            breeder_type: null,
            geolocation: null,
            remember_token: null,
            photo: null,
        }
    }

    static importTemplateHeaders() {
        const exclude = ['id','user_id','remember_token','photo','password_confirmation'];
        return Object.keys(this.createForm()).filter(k => !exclude.includes(k));
    }

    static importTemplateDropdowns() {
        return {
            breeder_type: ['Public', 'Private'],
            educ_level: [
                'Bachelor\'s',
                'Master\'s',
                'Doctorate/PhD',
            ],
            geolocation: [
                // Populated from API
            ]
        };
    }

    static updateForm(oldValue: Partial<Breeder>)
    {
        return {
            id: oldValue.id ?? null,
            user_id: oldValue.user_id ?? null,
            fname: oldValue.fname ?? null,
            mname: oldValue.mname ?? null,
            lname: oldValue.lname ?? null,
            suffix: oldValue.suffix ?? null,
            mobile_no: oldValue.mobile_no ?? null,
            email: oldValue.email ?? null,
            breeder_type: oldValue.breeder_type ?? null,
            //@ts-ignore
            password: oldValue.password ?? null,
            affiliation: oldValue.affiliated ? oldValue.affiliated.id : null,
            position: oldValue.position ?? null,
            educ_level: oldValue.educ_level ?? null,
            expertise: oldValue.expertise ?? null,
            research_interest: oldValue.research_interest ?? null,
            geolocation: oldValue.location ? oldValue.location.id : null,
            photo: oldValue.photo ? oldValue.photo : null,
        }
    }

    static getCreateFieldTitles() {
        return {
            fname: 'E.g. Juan',
            mname: 'E.g. Miguel',
            lname: 'E.g. Dela Cruz',
            suffix: 'E.g. Jr./Sr./IV',
            mobile_no: 'Format: 09XX-XXX-XXXX',
            email: 'Active email address',
            password: 'Must be 8 character long',
            password_confirmation: 'Repeat password',
            affiliation: "Office/Agency/Organization",
            position: 'Position',
            educ_level: "Highest Attained Education",
            expertise: "Separate with comma",
            research_interest: "Separate with comma",
            breeder_type: 'Government funded (Public) or Private funded (Private)',
            geolocation: 'Geographic location of the breeder',
            photo: '5mb max file size',
        };
    }

    static getUpdateFieldTitles() {
        return this.getCreateFieldTitles();
    }

    static getCardColumns() {
        return [
            {
                title: 'Profile Photo',
                key: 'photo',
                align: 'center',
                visible: false,
            },
            {
                title: 'Breeder No.',
                key: 'id',
                align: 'center',
                visible: true,
            },
            {
                title: 'User ID',
                key: 'user_id',
                align: 'center',
                visible: false,
            },
            {
                title: 'Name',
                key: 'getFullName',
                align: 'center',
                visible: true,
            },
            {
                title: 'Affiliation',
                key: 'affiliated.name',
                align: 'center',
                visible: true,
            },
            {
                title: 'Phone',
                key: 'mobile_no',
                align: 'center',
                visible: true,
            },
            {
                title: 'Email',
                key: 'email',
                align: 'center',
                visible: true,
            },
            {
                title: 'Breeding Crops',
                key: 'commoditiesList',
                align: 'text-center',
                visible: true,
            },
            {
                title: 'Type',
                key: 'breeder_type',
                align: 'text-center',
                visible: true,
            },
            {
                title: 'Address',
                key: 'location.getFullAddress',
                align: 'center',
                visible: true,
            },
            {
                title: 'Updated At',
                key: 'updated_at',
                align: 'center',
                visible: false,
            },
            {
                title: 'Created At',
                key: 'created_at',
                align: 'center',
                visible: false,
            },
            {
                title: 'Deleted At',
                key: 'deleted_at',
                align: 'center',
                visible: false,
            },
        ];
    }

    static getColumns() {
        return [
            {
                title: 'ID',
                key: 'id',
                db_key: 'id',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'User ID',
                key: 'user_id',
                db_key: 'user_id',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Name',
                key: 'getFullName',
                db_key: 'name',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Affiliation',
                key: 'affiliated.name',
                db_key: 'affiliated',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Position',
                key: 'position',
                db_key: 'position',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Education Level',
                key: 'educ_level',
                db_key: 'educ_level',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Phone',
                key: 'mobile_no',
                db_key: 'mobile_no',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Email',
                key: 'email',
                db_key: 'email',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Type',
                key: 'breeder_type',
                db_key: 'breeder_type',
                align: 'text-center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Commodities',
                key: 'commoditiesCount',
                db_key: 'commodities.count',
                align: 'text-center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Address',
                key: 'location.getFullAddress',
                db_key: 'location',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Expertise',
                key: 'expertise',
                db_key: 'expertise',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Research Interest',
                key: 'research_interest',
                db_key: 'research_interest',
                align: 'center',
                sortable: true,
                visible: true,
            },
            {
                title: 'Updated At',
                key: 'updated_at',
                db_key: 'updated_at',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Created At',
                key: 'created_at',
                db_key: 'created_at',
                align: 'center',
                sortable: true,
                visible: false,
            },
            {
                title: 'Deleted At',
                key: 'deleted_at',
                db_key: 'deleted_at',
                align: 'center',
                sortable: true,
                visible: false,
            },
        ];
    }
}
