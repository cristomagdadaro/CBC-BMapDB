<script>
import CaretDown from "@/Components/Icons/CaretDown.vue";
import CustomDropdown from "@/Components/CustomDropdown/CustomDropdown.vue";
import SearchBy from "@/Components/CRCMDatatable/Components/SearchBy.vue";
import SearchBox from "@/Components/CRCMDatatable/Components/SearchBox.vue";
import Commodity from "@/Pages/Projects/BreedersMap/domain/Commodity";
import CrcmTable from "@/Components/CRCMDatatable/Components/CrcmTable.vue";
import CrcmThead from "@/Components/CRCMDatatable/Components/CrcmThead.vue";
import TheadRow from "@/Components/CRCMDatatable/Components/TheadRow.vue";
import TH from "@/Components/CRCMDatatable/Components/TH.vue";
import CrcmTbody from "@/Components/CRCMDatatable/Components/CrcmTbody.vue";
import TbodyRow from "@/Components/CRCMDatatable/Components/TbodyRow.vue";
import TD from "@/Components/CRCMDatatable/Components/TD.vue";
import FilterIcon from "@/Components/Icons/FilterIcon.vue";
import CollapsableMenu from "@/Components/Collapsable/CollapsableMenu/CollapsableMenu.vue";
import BreedersMapOnboarding
    from "@/Pages/Projects/BreedersMap/presentation/components/OnboardingBM/BreedersMapOnboarding.vue";
import TransitionContainer from "@/Components/CustomDropdown/Components/TransitionContainer.vue";
import LoaderIcon from "@/Components/Icons/LoaderIcon.vue";
import DataFiltrationFields
    from "@/Pages/Projects/BreedersMap/presentation/components/map/components/DataFiltrationFields.vue";
import BarGraph from "@/Pages/Projects/BreedersMap/presentation/components/summary/components/BarGraph.vue";
import DoughnutGraph from "@/Pages/Projects/BreedersMap/presentation/components/summary/components/DoughnutGraph.vue";
import LineGraph from "@/Pages/Projects/BreedersMap/presentation/components/summary/components/LineGraph.vue";
import Breeder from "@/Pages/Projects/BreedersMap/domain/Breeder";
import DashboardShell from '@/Components/DashboardShell.vue';

export default {
    name: "PublicSummary",
    components: {
        LineGraph,
        DoughnutGraph,
        BarGraph,
        DataFiltrationFields,
        LoaderIcon,
        TransitionContainer,
        BreedersMapOnboarding,
        CollapsableMenu,
        FilterIcon,
        TD,
        TbodyRow,
        CrcmTbody, TH, TheadRow, CrcmThead, CrcmTable, SearchBox, SearchBy, CustomDropdown, CaretDown,
        DashboardShell,
    },
    data() {
        return {
            tables: [
                {label: 'By Commodities', name: 'commodities', route: '/api/commodities/summary', model: Commodity},
                {label: 'By Breeders', name: 'breeders', route: '/api/breeders/summary', model: Breeder},
            ],
            filter: {
                search: null,
                is_exact: false,
                filter: null,
                table_name: 'commodities',
                commodity: null,
                geo_location_filter: 'region',
                geo_location_value: null,
            },
            showListOfPlaces: false,
            listOfColors: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                'rgba(75, 192, 192, 0.5)',
                'rgba(153, 102, 255, 0.5)',
                'rgba(255, 159, 64, 0.5)'
            ],
            apiResponse: {
                chart_data: [],
                chart_labels: [],
                linechart_data: {labels: [], datasets: []},
                raw_data: [],
            },
            isLoading: false,
            lastUpdated: null,
            refreshKey: 0,
        }
    },
    computed: {
        Commodity() {
            return Commodity
        },
        tableModel() {
            return this.filter.table_name === 'commodities' ? Commodity : Breeder;
        },
        visibleColumns() {
            return this.tableModel.getCardColumns().filter(c => c.visible);
        },
        barGraphData() {
            const items = this.apiResponse?.chart_data || [];
            return {
                labels: items.map(i => i.label),
                datasets: [{label: 'By Region', data: items.map(i => i.total), backgroundColor: this.listOfColors}]
            };
        },
        doughnutGraphData() {
            const items = this.apiResponse?.chart_labels || [];
            return {
                labels: items.map(i => i.label),
                datasets: [{data: items.map(i => i.total), backgroundColor: this.listOfColors}]
            };
        },
        lineGraphData() {
            return this.apiResponse?.linechart_data || {labels: [], datasets: []};
        },
    },
    mounted() {
        this.lastUpdated = new Date().toISOString();
    },
    methods: {
        getNestedValue(obj, path) {
            return path.split('.').reduce((acc, part) => acc && acc[part], obj);
        },
        updateFilters(param, value) {
            this.filter[param] = value;
        },
        refreshDashboard() {
            this.refreshKey++;
            this.lastUpdated = new Date().toISOString();
        }
    }
}
</script>

<template>
    <DashboardShell
        title="BreedersMap Summary (Public)"
        :isLoading="isLoading"
        :lastUpdated="lastUpdated"
        @refresh="refreshDashboard"
    >
        <div class="flex flex-col gap-2">
            <breeders-map-onboarding/>
            <div class="relative sm:p-4 p-1 ">
                <data-filtration-fields
                    :key="refreshKey"
                    :tables="tables"
                    :params="filter"
                    @dataRefreshed="apiResponse = $event"
                    @updatedFilter="filter = $event"
                />

                <div id="bm-data-charts"
                     class="flex flex-col md:flex-row justify-evenly items-center my-5 gap-0.5 overflow-x-auto">
                    <div v-if="apiResponse && apiResponse.chart_data && !filter.search" class="flex justify-center"
                         style="width: 50%; height: auto">
                        <bar-graph :data="barGraphData"/>
                    </div>
                    <div v-if="apiResponse && apiResponse.chart_labels && !filter.commodity" class="flex justify-center"
                         style="width: 30%; height: auto">
                        <doughnut-graph :data="doughnutGraphData"/>
                    </div>
                    <div v-if="apiResponse && apiResponse.linechart_data && filter.commodity" class="flex justify-center"
                         style="width: 50%; height: auto">
                        <line-graph :data="lineGraphData"/>
                    </div>
                </div>

                <div id="bm-data-table" v-if="apiResponse && apiResponse.raw_data" class="text-xs overflow-x-auto">
                    <crcm-table>
                        <crcm-thead>
                            <thead-row>
                                <t-h v-for="column in tableModel.getCardColumns()" :visible="column.visible"
                                     :sortable="column.sortable" :key="column.key + column.title" :column="column.title"/>
                            </thead-row>
                        </crcm-thead>
                        <crcm-tbody class="max-h-[100vh] overflow-y-auto">
                            <tbody-row v-if="apiResponse && apiResponse.raw_data.length"
                                       v-for="row_data in apiResponse.raw_data">
                                <t-d v-for="column in visibleColumns" :key="column.key + row_data[column.key]"
                                     :visible="column.visible" :class="column?.class">
                                    {{ getNestedValue(row_data, column.key) }}
                                </t-d>
                            </tbody-row>
                            <tbody-row v-else>
                                <t-d class="text-center text-gray-500" colspan="8">No Data Found</t-d>
                            </tbody-row>
                        </crcm-tbody>
                    </crcm-table>
                </div>
            </div>
        </div>
</template>

