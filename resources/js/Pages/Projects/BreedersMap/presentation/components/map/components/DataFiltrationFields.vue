<script>
import CollapsableMenu from "@/Components/Collapsable/CollapsableMenu/CollapsableMenu.vue";
import CustomDropdown from "@/Components/CustomDropdown/CustomDropdown.vue";
import CaretDown from "@/Components/Icons/CaretDown.vue";
import DataFiltrationMixin from "@/Pages/mixins/DataFiltrationMixin";
import LoaderIcon from "@/Components/Icons/LoaderIcon.vue";
import SearchBox from "@/Components/CRCMDatatable/Components/SearchBox.vue";
import SearchBy from "@/Components/CRCMDatatable/Components/SearchBy.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import Commodity from "@/Pages/Projects/BreedersMap/domain/Commodity";
export default {
    name: "DataFiltrationFields",
    components: {CloseIcon, SearchBy, SearchBox, LoaderIcon, CaretDown, CustomDropdown, CollapsableMenu},
    mixins: [DataFiltrationMixin],
    created() {
        this.apiUrl = this.tables.length ? this.tables[0].route : null;

        this.filter =  {
            is_exact: false,
            filter: null,
            search: null,
            table_name: 'commodities',
            commodity: this.params?.commodity,
            geo_location_filter: this.params ? this.params.commodity: 'region',
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
}
</script>

<template>
    <div v-if="api && filter" class="flex flex-col gap-1">
        <div v-show="api.processing" class="absolute z-[900] flex gap-2 items-center justify-center top-0 left-0 w-full h-full bg-gray-200 opacity-75">
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
    </div>
</template>

<style scoped>

</style>
