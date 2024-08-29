<script>
import ApiService from "@/Modules/core/infrastructure/ApiService";
import CaretDown from "@/Components/Icons/CaretDown.vue";
import CustomDropdown from "@/Components/CustomDropdown/CustomDropdown.vue";
import { Bar, Doughnut } from 'vue-chartjs'
import {Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement} from 'chart.js'
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

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement)
export default {
    name: "Summary",
    components: {
        TD,
        Doughnut,
        TbodyRow,
        CrcmTbody, TH, TheadRow, CrcmThead, CrcmTable, SearchBox, SearchBy, CustomDropdown, CaretDown, Bar},
    data() {
        return {
            api: null,
            apiResponse: {},
            filter: {
                group_by: 'region',
                search: null,
                is_exact: false,
                filter: null,
            },
            showListOfPlaces: false,
            listOfColors: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 99, 132, 0.2)',
            ]
        }
    },
    mounted() {
        this.api = new ApiService(route('api.breedersmap.commodities.summary.public'));
        this.getSummary();
    },
    computed: {
        Commodity() {
            return Commodity
        },
        data() {
            return this.apiResponse;
        },
        groupBy() {
            return this.filter.group_by;
        },
        chartOptions() {
            return {
                responsive: true,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        position: 'top',
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                return `There are ${tooltipItem.raw} commodities`;
                            }
                        }
                    },
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'No. of Commodities'
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: false,
                            text: this.filter,
                        },
                        grid: {
                            display: false
                        }
                    }
                },
            }
        },
        piechartOptions() {
            return {
                responsive: true,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        position: 'bottom',
                        display: true
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                return `There are ${tooltipItem.raw} research studies of ${tooltipItem.label} in this slice`;
                            }
                        }
                    },
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'No. of Commodities'
                        },
                        grid: {
                            display: false
                        },
                        display: false
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: false,
                            text: this.filter,
                        },
                        grid: {
                            display: false
                        },
                        display: false
                    }
                },
            }
        },
    },
    methods: {
        async getSummary() {
            await this.api.get({
                group_by: this.filter.group_by,
                search: this.filter.search,
                is_exact: this.filter.is_exact,
                filter: this.filter.filter,
            }).then(response => {
                this.apiResponse = response.data;
            });
        },
        updateFilters(key, value) {
            this.filter[key] = value;
            this.getSummary();
        },
        changeListOf(value) {
            // change the api url whether for breeders or commodities
            if (value === 'breeders') {
                this.api = new ApiService(route('api.breedersmap.breeders.summary.public'));
            } else {
                this.api = new ApiService(route('api.breedersmap.commodities.summary.public'));
            }
        }
    }
}
</script>

<template>
    <div class="shadow-lg bg-gray-100 rounded-md sm:p-4 p-1 flex flex-col gap-2">
        <div>
            <div class="flex flex-col gap-1">
                <div class="flex flex-row gap-1">
                    <custom-dropdown
                        :withAllOption="false"
                        :options="[
                                    {label: 'Breeders', name: 'breeders'},
                                    {label: 'Commodity', name: 'common_name'},
                                 ]"
                        placeholder="Select a list"
                        @selectedChange="changeListOf($event); getSummary($event);">
                        <template #icon>
                            <caret-down  class="h-4 w-4 text-gray-700" />
                        </template>
                    </custom-dropdown>
                    <custom-dropdown
                        :value="filter.group_by"
                        :withAllOption="false"
                        :options="[
                                    {label: 'Region', name: 'region'},
                                    {label: 'Province', name: 'province'},
                                    {label: 'City', name: 'city'},
                                 ]"
                        placeholder="Group by"
                        @selectedChange="filter.group_by = $event; filter.search = null; getSummary($event);">
                        <template #icon>
                            <caret-down  class="h-4 w-4 text-gray-700" />
                        </template>
                    </custom-dropdown>
                    <custom-dropdown
                        v-if="data && data.chart_data"
                        :value="filter.search"
                        :withAllOption="false"
                        :options="data.chart_data.map( item => {
                                    return {label: item.label, name: item.label}
                                 })"
                        :placeholder="`Select a specific ${filter.group_by}`"
                        @selectedChange="filter.search = $event; getSummary($event)">
                        <template #icon>
                            <caret-down  class="h-4 w-4 text-gray-700" />
                        </template>
                    </custom-dropdown>
                </div>
            </div>
            <div>
                <Bar
                    v-if="data.chart_data"
                    id="my-chart-id"
                    :options="chartOptions"
                    :data="{
                    labels: data.chart_data.map(item => item.label),
                    datasets: [
                      {
                        label: 'By Region',
                        data: data.chart_data.map(item => item.total),
                        backgroundColor: listOfColors,
                        borderColor: listOfColors.map(color => color.replace('0.2', '1')),
                        borderWidth: 1
                      },
                    ]
                  }"
                />
                <Doughnut
                    v-if="data.commodities_chart"
                    id="my-chart-id"
                    :options="piechartOptions"
                    :data="{
                    labels: data.commodities_chart.map(item => item.label),
                    datasets: [
                      {
                        data: data.commodities_chart.map(item => item.total),
                        backgroundColor: listOfColors,
                        borderColor: listOfColors.map(color => color.replace('0.2', '1')),
                        borderWidth: 1
                      }
                    ]
                  }"
                />
            </div>
            <div class="w-full flex flex-row gap-1 my-2">
                <search-box
                    :value="filter.search"
                    :options="{}"
                    :label="filter.search ?? 'Select a place'"
                    @searchString="updateFilters('search', $event)"
                    @keydown.enter="updateFilters('search', $event.target.value)"
                    @focusin="showListOfPlaces = true"
                    class="w-full"
                />
                <search-by :value="filter.filter"
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
            <div v-if="data.commodities" class="text-xs">
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
                    <crcm-tbody>
                        <tbody-row v-for="commodity in data.commodities">
                            <t-d>{{ commodity.name }}</t-d>
                            <t-d>{{ commodity.scientific_name }}</t-d>
                            <t-d>{{ commodity.variety }}</t-d>
                            <t-d>{{ commodity.accession }}</t-d>
                            <t-d>{{ commodity.germplasm }}</t-d>
                            <t-d>{{ commodity.population }}</t-d>
                            <t-d>{{ commodity.maturity_period }}</t-d>
                            <t-d>{{ commodity.yield }}</t-d>
                        </tbody-row>
                    </crcm-tbody>
                </crcm-table>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
