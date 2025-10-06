<script>
import CollapsableMenu from "@/Components/Collapsable/CollapsableMenu/CollapsableMenu.vue";
import DataFiltrationMixin from "@/Pages/mixins/DataFiltrationMixin";
import LoaderIcon from "@/Components/Icons/LoaderIcon.vue";
import SearchBox from "@/Components/CRCMDatatable/Components/SearchBox.vue";
import SearchBy from "@/Components/CRCMDatatable/Components/SearchBy.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import Commodity from "@/Pages/Projects/BreedersMap/domain/Commodity";
import SelectField from "@/Components/Form/SelectField.vue";
export default {
    name: "DataFiltrationFields",
    components: {SelectField, CloseIcon, SearchBy, SearchBox, LoaderIcon, CollapsableMenu},
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
            <SelectField
                id="bm-listfilter-dropdown"
                label="Select a list"
                :disabled="api.processing"
                v-model="filter.table_name"
                :options="dataTables"
                placeholder="Select a list"
                :searchable="false"
                :clearable="false"
                @change="!api.processing ? changeListOf($event?.value) : null"
            />

            <SelectField
                id="bm-commodityfilter-dropdown"
                v-if="data && !!data.raw_data_labels && filter.table_name === 'commodities'"
                label="Select a specific commodity"
                v-model="filter.commodity"
                :options="commodityLabels"
                placeholder="None"
                :disabled="api.processing"
                @change="!api.processing ? changeCommodity($event?.value) : null"
            />

            <SelectField
                id="bm-locationfilter-dropdown"
                label="Group by"
                v-model="filter.geo_location_filter"
                :options="locationLabels"
                placeholder="None"
                :disabled="api.processing"
                :searchable="false"
                :clearable="false"
                @change="!api.processing ? changeLocation($event?.value) : null"
            />

            <SelectField
                id="bm-cprfilter-dropdown"
                v-if="data && data.group_search_labels && filter.geo_location_filter !== 'affiliation'"
                :label="`Select a specific ${filter.geo_location_filter}`"
                v-model="filter.geo_location_value"
                :options="specificLocationLabels"
                placeholder="None"
                :disabled="api.processing"
                @change="!api.processing ? changeSpecificLocation($event?.value) : null"
            />

            <SelectField
                id="bm-cprfilter-institute-dropdown"
                v-if="data && data.group_search_institute && filter.geo_location_filter === 'affiliation'"
                label="Select a specific institute"
                v-model="filter.group_search_institute"
                :options="specificInstituteLabels"
                placeholder="None"
                :disabled="api.processing"
                @change="changeSpecificLocation($event?.value)"
            />
        </collapsable-menu>
    </div>
</template>

<style scoped>

</style>
