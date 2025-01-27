<script>
import CollapsableMenu from "@/Components/Collapsable/CollapsableMenu/CollapsableMenu.vue";
import CustomDropdown from "@/Components/CustomDropdown/CustomDropdown.vue";
import CaretDown from "@/Components/Icons/CaretDown.vue";
import DataFiltrationMixin from "@/Pages/mixins/DataFiltrationMixin";
import LoaderIcon from "@/Components/Icons/LoaderIcon.vue";
import SearchBox from "@/Components/CRCMDatatable/Components/SearchBox.vue";
import SearchBy from "@/Components/CRCMDatatable/Components/SearchBy.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
export default {
    name: "DataFiltrationFields",
    components: {CloseIcon, SearchBy, SearchBox, LoaderIcon, CaretDown, CustomDropdown, CollapsableMenu},
    mixins: [DataFiltrationMixin],
    props: {
        tables: {
            type: Array,
            required: true,
        },
        params: {
            type: Object,
            required: false,
            default: () => { return {  } },
        },
    },
    created() {
        this.apiUrl = this.tables.length ? this.tables[0].route : null;
        this.filter =  {
            is_exact: true,
            filter: null,
            search: null,
            table_name: 'commodities',
            commodity: null,
            geo_location_filter: 'region',
            geo_location_value: null,
            filter_by_parent_column:  this.filter_by_parent_column,
            filter_by_parent_id: this.$page.props.commodity ? this.$page.props.commodity.id : null,
        }
    },
    data() {
        return {
            showListOfPlaces: false,
        };
    },
    computed: {
        Commodity() {
            return Commodity
        },
        filter_by_parent_column() {
            if (this.filter.table_name === 'commodities')
                return 'breeder_id';
            return null;
        }
    },
    watch: {
        params: {
            handler(newVal) {
                this.changeSearch(newVal.search);
            },
            deep: true,
        }
    },
}
</script>

<template>
    <div v-if="api && filter" class="flex flex-col gap-1">
        <div v-show="api.processing" class="absolute z-[900] flex gap-2 items-center justify-center top-0 w-full h-full bg-gray-100 opacity-75">
            <div class="flex items-center gap-2 justify-center">
                <loader-icon /> Processing...
            </div>
        </div>
        <collapsable-menu id="bm-filter-dropdown" label="Filters" open-default>
            <custom-dropdown
                id="bm-listfilter-dropdown"
                label="Select a list"
                :disabled="api.processing"
                :value="filter.table_name"
                :withAllOption="false"
                :options="dataTables"
                placeholder="Select a list"
                @selectedChange="!api.processing ? changeListOf($event) : null">
                <template #icon>
                    <caret-down  class="h-4 w-4 text-gray-700" />
                </template>
            </custom-dropdown>
            <custom-dropdown
                id="bm-commodityfilter-dropdown"
                v-if="data && !!data.raw_data_labels && filter.table_name === 'commodities'"
                label="Select a specific commodity"
                searchable
                :value="filter.commodity"
                :withAllOption="false"
                :options="commodityLabels"
                placeholder="None"
                @selectedChange="!api.processing ? changeCommodity($event) : null">
                <template #icon>
                    <caret-down  class="h-4 w-4 text-gray-700" />
                </template>
            </custom-dropdown>
            <custom-dropdown
                id="bm-locationfilter-dropdown"
                label="Group by"
                :value="filter.geo_location_filter"
                :withAllOption="false"
                :options="locationLabels"
                placeholder="None"
                @selectedChange="!api.processing ? changeLocation($event) : null">
                <template #icon>
                    <caret-down  class="h-4 w-4 text-gray-700" />
                </template>
            </custom-dropdown>
            <custom-dropdown
                id="bm-cprfilter-dropdown"
                v-if="data && data.group_search_labels && filter.geo_location_filter !== 'affiliation'"
                searchable
                :label="`Select a specific ${filter.geo_location_filter}`"
                :value="filter.geo_location_value"
                :withAllOption="false"
                :options="specificLocationLabels"
                placeholder="None"
                @selectedChange="!api.processing ? changeSpecificLocation($event) : null">
                <template #icon>
                    <caret-down  class="h-4 w-4 text-gray-700" />
                </template>
            </custom-dropdown>
            <custom-dropdown
                id="bm-cprfilter-dropdown"
                v-if="data && data.group_search_institute && filter.geo_location_filter === 'affiliation'"
                searchable
                :label="`Select a specific institute`"
                :value="filter.group_search_institute"
                :withAllOption="false"
                :options="specificInstituteLabels"
                placeholder="None"
                @selectedChange="changeSpecificLocation($event)">
                <template #icon>
                    <caret-down  class="h-4 w-4 text-gray-700" />
                </template>
            </custom-dropdown>
        </collapsable-menu>
        <div class="w-full flex flex-col gap-1 relative hidden" v-if="model">
            <div class="w-full flex gap-1 relative">
                <search-box
                    id="bm-search-box"
                    :value="filter.search"
                    :placeholder='"Search a commodity or breeder by " + model.getColumns().find(column => column.db_key === filter.filter)?.title'
                    @focusin="showListOfPlaces = true"
                    @searchString="!api.processing ? changeSearch($event) : null"
                    class="w-full"
                />
                <search-by id="bm-columnsfilter-dropdown"
                           @searchBy="changeSearchBy($event)"
                           @isExact="changeIsExact($event)"
                           :is-exact="filter.is_exact"
                           :value="filter.filter"
                           :options="model.getColumns().map(column => { return { name: column.db_key, label: column.title } })" />
                <div>
                    {{ filter }}
                </div>
            </div>
            <div v-if="showListOfPlaces" class="absolute top-16 rounded border-2 z-[999] bg-gray-100 shadow flex-col flex gap-1 overflow-y-auto max-h-96 p-2">
                <div
                    v-if="data && data.raw_data"
                    v-for="point in data.raw_data"
                    :key="point.id"
                    class="flex flex-row items-center gap-1 border p-1 py-2 rounded hover:bg-gray-200 leading-1 duration-200 select-none"
                >
                    <p v-if="point instanceof model" class="font-medium leading-5 px-1 w-full flex items-center">
                        <b>{{ point.breeder.getFullName }}</b>&nbsp;(<i class="font-light">{{ point.name }}</i>)
                        <close-icon @click="showListOfPlaces = false" class="h-4 w-4 hover:scale-110 duration-200" />
                    </p>
                    <p v-else-if="point instanceof model" class="font-medium leading-5 px-1 w-full flex items-center">
                        <b>{{ point.getFullName }} </b>&nbsp;(<i class="font-light">{{ point.affiliated.name }}</i>)
                        <close-icon @click="showListOfPlaces = false" class="h-4 w-4 hover:scale-110 duration-200" />
                    </p>
                </div>
                <div
                    v-else
                    class="flex flex-row items-center gap-1 p-1 py-2 rounded hover:bg-gray-200 leading-1 duration-200 select-none">
                    <h1 class="font-medium leading-5 px-1 w-full flex items-center justify-between">
                        No data available
                    </h1>
                </div>
            </div>
        </div>

    </div>
</template>

<style scoped>

</style>
