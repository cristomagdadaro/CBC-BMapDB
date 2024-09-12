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
import BreedersMapOnboarding from "@/Pages/Projects/BreedersMap/presentation/components/OnboardingBM/BreedersMapOnboarding.vue";
import TransitionContainer from "@/Components/CustomDropdown/Components/TransitionContainer.vue";
import LoaderIcon from "@/Components/Icons/LoaderIcon.vue";
import DataFiltrationFields from "@/Pages/Projects/BreedersMap/presentation/components/map/components/DataFiltrationFields.vue";
import BarGraph from "@/Pages/Projects/BreedersMap/presentation/components/summary/components/BarGraph.vue";
import DoughnutGraph from "@/Pages/Projects/BreedersMap/presentation/components/summary/components/DoughnutGraph.vue";
import LineGraph from "@/Pages/Projects/BreedersMap/presentation/components/summary/components/LineGraph.vue";

export default {
    name: "Summary",
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
        CrcmTbody, TH, TheadRow, CrcmThead, CrcmTable, SearchBox, SearchBy, CustomDropdown, CaretDown},
    data() {
        return {
            filter: {
                search: null,
                is_exact: true,
                filter: null,
                table_name: 'commodities',
                commodity: null,
            },

            showListOfPlaces: false,
            colorOpacity: 1,
            listOfColors: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                'rgba(75, 192, 192, 0.5)',
                'rgba(153, 102, 255, 0.5)',
                'rgba(255, 159, 64, 0.5)',
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                'rgba(75, 192, 192, 0.5)',
                'rgba(153, 102, 255, 0.5)',
                'rgba(255, 159, 64, 0.5)',
                'rgba(255, 99, 132, 0.5)',
            ],
            listOfTables: [
                {
                    label: 'Breeders',
                    name: 'breeders',
                    route: 'api.breedersmap.breeders.summary.public'
                },
                {
                    label: 'Commodity',
                    name: 'commodities',
                    route: 'api.breedersmap.commodities.summary.public'
                },
            ],
            apiResponseMixin: [],
        }
    },
    watch: {
        'colorOpacity': function (value) {
            this.listOfColors = this.listOfColors.map(color => color.replace(/0.\d/, value));
        },
    },
    computed: {
        doughnutGraph() {
            return doughnutGraph
        },
        Commodity() {
            return Commodity
        },
        barGraphData() {
            return {
                labels: this.apiResponseMixin.chart_data.map(item => item.label),
                datasets: [
                    {
                        label: 'By Region',
                        data: this.apiResponseMixin.chart_data.map(item => item.total),
                        backgroundColor: this.listOfColors,
                        borderColor: this.listOfColors.map(color => color.replace('0.2', this.colorOpacity)),
                        borderWidth: 1,
                    },
                ]
            }
        },
        doughnutGraphData() {
            return {
                labels: this.apiResponseMixin.commodities_chart.map(item => item.label),
                datasets: [
                    {
                        data: this.apiResponseMixin.commodities_chart.map(item => item.total),
                        backgroundColor: this.listOfColors,
                        borderColor: this.listOfColors.map(color => color.replace('0.2', '1')),
                        borderWidth: 1
                    }
                ]
            }
        },
        lineGraphData() {
            return {
                labels: this.apiResponseMixin.commodities_linechart.labels,
                datasets: this.apiResponseMixin.commodities_linechart.datasets
            }
        },
    },
}
</script>

<template>
    <div class="flex flex-col gap-2">
        <breeders-map-onboarding />
        <div class="relative sm:p-4 p-1 ">
<!--            <div id="bm-coloropacity-slider" class="flex flex-col gap-1">
                <label>Color Opacity: {{colorOpacity}}</label>
                <input type="range" min="0.01" max="1.1" step="0.01" v-model="colorOpacity">
            </div>-->
            <data-filtration-fields :tables="listOfTables"
                                    @dataRefreshed="apiResponseMixin = $event"
                                    @updatedFilter="filter = $event"
            />
            <div id="bm-data-charts" class="flex flex-col md:flex-row justify-evenly items-center my-5 gap-0.5 overflow-x-auto">
                <div v-if="apiResponseMixin && apiResponseMixin.chart_data && !filter.search"
                     class="flex justify-center"
                     style="width: 50%; height: auto"
                >
                    <bar-graph :data="barGraphData" />
                </div>
                <div v-if="apiResponseMixin && apiResponseMixin.commodities_chart && !filter.commodity"
                     class="flex justify-center"
                     style="width: 30%; height: auto"
                >
                    <doughnut-graph :data="doughnutGraphData" />
                </div>
                <div v-if="apiResponseMixin && apiResponseMixin.commodities_linechart && filter.commodity"
                     class="flex justify-center"
                     style="width: 50%; height: auto"
                >
                    <line-graph :data="lineGraphData" />
                </div>
            </div>
            <div class="w-full flex flex-row gap-1 my-2">
                <search-box
                    id="bm-search-box"
                    :value="filter.search"
                    :options="{}"
                    :label="filter.search ?? 'Select a place'"
                    @searchString="updateFilters('search', $event)"
                    @keydown.enter="filterResponse($event.target.value)"
                    @focusin="showListOfPlaces = true"
                    class="w-full"
                />
                <search-by id="bm-columnsfilter-dropdown"
                           :value="filter.filter"
                           :is-exact="filter.is_exact"
                           :options="Commodity.getColumns().map(column => {
                                 return {
                                      name: column.key,
                                      label: column.title
                                 }
                           })"
                           @isExact="filter.is_exact = $event"
                           @searchBy="filter.filter = $event"
                />
            </div>
            <div id="bm-data-table" v-if="apiResponseMixin && apiResponseMixin.commodities" class="text-xs overflow-x-auto">
                <crcm-table>
                    <crcm-thead>
                        <thead-row>
                            <t-h column="Commodity" />
                            <t-h column="Scientific Name" />
                            <t-h column="Variety" />
                            <t-h column="Accession" />
                            <t-h column="Germplasm" />
                            <t-h column="Population" />
                            <t-h column="Maturity Period" />
                            <t-h column="Yield" />
                        </thead-row>
                    </crcm-thead>
                    <crcm-tbody class="max-h-[100vh] overflow-y-auto">
                        <tbody-row v-if="apiResponseMixin && apiResponseMixin.commodities.length" v-for="commodity in apiResponseMixin.commodities">
                            <t-d>{{ commodity.name }}</t-d>
                            <t-d>{{ commodity.scientific_name }}</t-d>
                            <t-d>{{ commodity.variety }}</t-d>
                            <t-d>{{ commodity.accession }}</t-d>
                            <t-d>{{ commodity.germplasm }}</t-d>
                            <t-d>{{ commodity.population }}</t-d>
                            <t-d>{{ commodity.maturity_period }}</t-d>
                            <t-d>{{ commodity.yield }}</t-d>
                        </tbody-row>
                        <tbody-row v-else>
                            <t-d class="text-center text-gray-500" colspan="8">No Data Found</t-d>
                        </tbody-row>
                    </crcm-tbody>
                </crcm-table>
            </div>
            <div v-if="apiResponseMixin && apiResponseMixin.breeders" class="text-xs">
                <crcm-table>
                    <crcm-thead>
                        <thead-row>
                            <t-h column="Breeder" />
                            <t-h column="Agency" />
                            <t-h column="Phone" />
                            <t-h column="Email" />
                        </thead-row>
                    </crcm-thead>
                    <crcm-tbody>
                        <tbody-row  v-if="apiResponseMixin && apiResponseMixin.breeders.length" v-for="breeder in apiResponseMixin.breeders">
                            <t-d>{{ breeder.name }}</t-d>
                            <t-d>{{ breeder.agency }}</t-d>
                            <t-d>{{ breeder.phone }}</t-d>
                            <t-d>{{ breeder.email }}</t-d>
                        </tbody-row>
                        <tbody-row v-else>
                            <t-d class="text-center text-gray-500" colspan="4">No Data Found</t-d>
                        </tbody-row>
                    </crcm-tbody>
                </crcm-table>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
