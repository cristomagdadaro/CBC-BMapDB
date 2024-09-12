import ApiService from "@/Modules/core/infrastructure/ApiService";

export default {
    data() {
        return {
            api: null,
            apiResponse: {},
            apiUrl: null,
            filter: {
                group_by: null,
                search: null,
                is_exact: true,
                filter: null,
                table_name: null,
                commodity: null,
            },
            tables: [
                { label: 'Commodity', name: 'commodities', route: 'api.breedersmap.commodities.summary.public' },
                { label: 'Breeders', name: 'breeders', route: 'api.breedersmap.breeders.summary.public' },
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
            return this.data.commodity_labels.map(item => ({ label: item, name: item }));
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
        }
    },
    methods: {
        async refreshData() {
            const params = {
                group_by: this.filter.group_by,
                search: this.filter.search,
                is_exact: this.filter.is_exact,
                filter: this.filter.filter,
                commodity: this.filter.commodity,
            };
            const response = await this.api.get(params);
            this.apiResponse = response.data;
        },
        async changeListOf(value) {
            if (value && this.tables.length) {
                this.filter.table_name = value;
                this.filter.search = null;
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
            this.filter.group_by = value;
            this.filter.search = null;
            await this.refreshData();
        },
        async changeSpecificLocation(value) {
            this.filter.search = value;
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
