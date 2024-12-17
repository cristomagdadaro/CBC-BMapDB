<script>
import CollapsableMenu from "@/Components/Collapsable/CollapsableMenu/CollapsableMenu.vue";
import CustomDropdown from "@/Components/CustomDropdown/CustomDropdown.vue";
import CaretDown from "@/Components/Icons/CaretDown.vue";
import DataFiltrationMixin from "@/Pages/mixins/DataFiltrationMixin";
import LoaderIcon from "@/Components/Icons/LoaderIcon.vue";
export default {
    name: "DataFiltrationFields",
    components: {LoaderIcon, CaretDown, CustomDropdown, CollapsableMenu},
    mixins: [DataFiltrationMixin],
    props: {
        tables: {
            type: Array,
            required: true,
        },
    },
    created() {
        this.apiUrl = this.tables.length ? this.tables[0].route : null;
        this.filter =  {
            is_exact: true,
            filter: null,
            table_name: 'commodities',
            commodity: null,
            geo_location_filter: 'region',
            geo_location_value: null,
            filter_by_parent_column:  this.filter_by_parent_column,
            filter_by_parent_id: this.$page.props.commodity ? this.$page.props.commodity.id : null,
        }
    },
    computed: {
        filter_by_parent_column() {
            if (this.filter.table_name === 'commodities')
                return 'breeder_id';
            return null;
        }
    },
    async mounted() {
       await this.refreshData()
    },
}
</script>

<template>
    <collapsable-menu v-if="api && filter" id="bm-filter-dropdown" label="Filters" open-default>
        <div v-show="api.processing" class="absolute z-[900] flex gap-2 items-center justify-center top-0 w-full h-full bg-gray-100 opacity-75">
            <div class="flex items-center gap-2 justify-center">
                <loader-icon /> Processing...
            </div>
        </div>
        <custom-dropdown
            id="bm-listfilter-dropdown"
            label="Select a list"
            :disabled="api.processing"
            :value="filter.table_name"
            :withAllOption="false"
            :options="dataTables"
            placeholder="Select a list"
            @selectedChange="changeListOf($event);">
            <template #icon>
                <caret-down  class="h-4 w-4 text-gray-700" />
            </template>
        </custom-dropdown>
        <custom-dropdown
            id="bm-commodityfilter-dropdown"
            v-if="data && data.raw_data_labels && filter.table_name === 'commodities'"
            label="Select a specific commodity"
            searchable
            :value="filter.commodity"
            :withAllOption="false"
            :options="commodityLabels"
            placeholder="None"
            @selectedChange="changeCommodity($event)">
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
            @selectedChange="changeLocation($event)">
            <template #icon>
                <caret-down  class="h-4 w-4 text-gray-700" />
            </template>
        </custom-dropdown>
        <custom-dropdown
            id="bm-cprfilter-dropdown"
            v-if="data && data.group_search_labels"
            searchable
            :label="`Select a specific ${filter.geo_location_filter}`"
            :value="filter.geo_location_value"
            :withAllOption="false"
            :options="specificLocationLabels"
            placeholder="None"
            @selectedChange="changeSpecificLocation($event)">
            <template #icon>
                <caret-down  class="h-4 w-4 text-gray-700" />
            </template>
        </custom-dropdown>
        <custom-dropdown
            id="bm-cprfilter-dropdown"
            v-if="data && data.group_search_institute"
            searchable
            :label="`Select a specific ${filter.group_search_institute}`"
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
</template>

<style scoped>

</style>
