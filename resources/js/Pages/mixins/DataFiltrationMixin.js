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
                is_exact: false,
                filter: null,
                search: null,
                table_name: null,
                commodity: null,
                geo_location_filter: null,
                geo_location_value: null,
                filter_by_parent_column: null,
                filter_by_parent_id: null,
                group_search_institute: null,
            },
            dataChanged: false, // Tracks whether data needs refreshing
            debounceRefresh: this.debounce(async function () {
                const params = {
                    geo_location_value: this.filter.geo_location_value,
                    is_exact: this.filter.is_exact,
                    filter: this.filter.filter,
                    search: this.filter.search,
                    commodity: this.filter.commodity,
                    geo_location_filter: this.filter.geo_location_filter,
                    filter_by_parent_column: this.filter.filter_by_parent_column,
                    filter_by_parent_id: this.filter.filter_by_parent_id,
                    group_search_institute: this.filter.group_search_institute,
                };
                const response = await this.api.get(params, this.model ?? null);
                this.apiResponse = response ? response.data : {};
            }, 300), // Debounced refreshData with a 300ms delay
        };
    },
    async mounted() {
        if (this.apiUrl) {
            this.api = new ApiService(this.apiUrl);
            await this.changeListOf();
        }
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
                { label: 'City', name: 'city' },
                {label: 'Institute', name: 'affiliation'}
            ];
        },
        specificLocationLabels() {
            return this.data.group_search_labels.map(item => ({ label: item, name: item }));
        },
        specificInstituteLabels() {
            return this.data.group_search_institute.map(item => ({ label: item.name, name: item.id }));
        }
    },
    methods: {
        debounce(fn, delay = 300) {
            let timeout;
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => fn.apply(this, args), delay);
            };
        },
        async changeListOf(value) {
            if (value) {
                this.model = this.tables.find(item => item.name === value).model;
                this.filter.table_name = value;
            } else {
                this.model = Commodity;
            }
            this.filter.geo_location_value = null;
            this.apiUrl = this.tables.find(item => item.name === value);

            if ((this.apiUrl))
                this.api = new ApiService(this.apiUrl.route);

            this.dataChanged = true;
        },
        async changeCommodity(value) {
            this.filter.commodity = value;
            this.dataChanged = true;
        },
        async changeLocation(value) {
            this.filter.geo_location_filter = value;
            this.filter.geo_location_value = null;
            this.dataChanged = true;
        },
        async changeSpecificLocation(value) {
            this.filter.geo_location_value = value;
            this.dataChanged = true;
        },
        async refreshData() {
            this.debounceRefresh();
        },
        async changeSearch(value) {
            this.filter.search = value;
            this.dataChanged = true;
        },
        async changeSearchBy(value) {
            this.filter.filter = value;
        },
        async changeIsExact(value) {
            this.filter.is_exact = value;
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
        },
        // Watch `dataChanged` and trigger refresh only once after multiple changes
        dataChanged: {
            immediate: false,
            async handler(newValue) {
                if (newValue) {
                    await this.refreshData();
                    this.dataChanged = false; // Reset the flag
                }
            },
        },
    },
    emits: ['dataRefreshed', 'tableChange', 'processingRequest','updatedFilter'],
};
