import ApiService from "@/Modules/core/infrastructure/ApiService";
import Commodity from "@/Pages/Projects/BreedersMap/domain/Commodity";
import Breeder from "@/Pages/Projects/BreedersMap/domain/Breeder";

export default {
    data() {
        return {
            model: null,
            api: null,
            apiResponse: {},
            apiUrl: null,
            filter: {
                is_exact: true,
                filter: null,
                table_name: null,
                commodity: null,
                geo_location_filter: null,
                geo_location_value: null,
            },
            tables: [
                { label: 'Commodity', name: 'commodities', route: 'api.breedersmap.commodities.summary.public', model: Commodity },
                { label: 'Breeders', name: 'breeders', route: 'api.breedersmap.breeders.summary.public', model: Breeder },
            ]
        };
    },
    async mounted() {
        if (this.apiUrl) {
            this.api = new ApiService(route(this.apiUrl));
            await this.changeListOf();
        }
        else
            console.error('API URL is not defined');
    },
    computed: {
        data() {
            return this.apiResponse;
        },
        dataTables() {
            return this.tables.map(item => ({ label: item.label, name: item.name }));
        },
        commodityLabels() {
            return this.data.raw_data_labels.map(item => ({ label: item, name: item }));
        },
        locationLabels() {
            return [
                { label: 'Region', name: 'region' },
                { label: 'Province', name: 'province' },
                { label: 'City', name: 'city' }
            ];
        },
        specificLocationLabels() {
            return this.data.group_search_labels.map(item => ({ label: item, name: item }));
        },
        specificInstituteLabels() {
            return this.data.group_search_institute.map(item => ({ label: item, name: item }));
        }
    },
    methods: {
        async refreshData() {
            const params = {
                geo_location_value: this.filter.geo_location_value,
                is_exact: this.filter.is_exact,
                filter: this.filter.filter,
                commodity: this.filter.commodity,
                geo_location_filter: this.filter.geo_location_filter,
            };
            const response = await this.api.get(params, this.model ?? null);
            if (response)
                this.apiResponse = response.data;
            else
                this.apiResponse = {};
        },
        async changeListOf(value) {
            if (value)
                this.model = this.tables.find(item => item.name === value).model;
            else
                this.model = Commodity;

            if (value && this.tables.length) {
                this.filter.table_name = value;
                this.filter.geo_location_value = null;
                this.apiUrl = this.tables.find(item => item.name === value);
                this.api = new ApiService(route(this.apiUrl.route));
            } else {
                this.api = new ApiService(route(this.apiUrl.route));
            }
            await this.refreshData();
        },
        async changeCommodity(value) {
            this.filter.filter = 'name';
            this.filter.commodity = value;
            await this.refreshData();
        },
        async changeLocation(value) {
            this.filter.geo_location_filter = value;
            this.filter.geo_location_value = null;
            await this.refreshData();
        },
        async changeSpecificLocation(value) {
            this.filter.geo_location_value = value;
            await this.refreshData();
        }
    },
    watch: {
        apiResponse: {
            handler(value) {
                this.$emit('dataRefreshed', value);
            },
            immediate: true,
        },
        'filter.table_name': {
            handler(value) {
                if (this.api)
                    this.$emit('tableChange', this.api._baseUrl);
            },
            immediate: true,
        },
        filter: {
            handler(value) {
                this.$emit('updatedFilter', value);
            },
            deep: true,
        },
        'api.processing': {
            handler(value) {
                this.$emit('processingRequest', value);
            },
            immediate: true,
        }
    },
    emits: ['dataRefreshed', 'tableChange', 'processingRequest','updatedFilter'],
};
