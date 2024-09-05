<script>
import ApiService from "@/Modules/core/infrastructure/ApiService";
import CaretDown from "@/Components/Icons/CaretDown.vue";
import CustomDropdown from "@/Components/CustomDropdown/CustomDropdown.vue";
import {Bar, Doughnut, Line} from 'vue-chartjs'
import {Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement, PointElement, LineElement} from 'chart.js'
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
import ChartDataLabels from 'chartjs-plugin-datalabels';
import FilterIcon from "@/Components/Icons/FilterIcon.vue";
import CollapsableMenu from "@/Components/Collapsable/CollapsableMenu/CollapsableMenu.vue";

ChartJS.register(ChartDataLabels, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement, PointElement, LineElement)
export default {
    name: "Summary",
    components: {
        CollapsableMenu,
        FilterIcon,
        TD,
        Doughnut,
        Line,
        TbodyRow,
        CrcmTbody, TH, TheadRow, CrcmThead, CrcmTable, SearchBox, SearchBy, CustomDropdown, CaretDown, Bar},
    data() {
        return {
            api: null,
            apiResponse: {},
            filter: {
                group_by: 'region',
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
        }
    },
    mounted() {
        this.api = new ApiService(route('api.breedersmap.commodities.summary.public'));
        this.getSummary();
    },
    watch: {
        'filter.table_name': function (value) {
            this.changeListOf(value);
        },
        'colorOpacity': function (value) {
            this.listOfColors = this.listOfColors.map(color => color.replace(/0.\d/, value));
        }
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
                                if (!tooltipItem.raw)
                                    return `There are no entries`;
                                else if (tooltipItem.raw > 1)
                                    return `There are ${tooltipItem.raw} entries`;
                                return `There is ${tooltipItem.raw} entry`;
                            }
                        }
                    },
                    datalabels: {
                        formatter: (value, ctx) => {
                            let sum = 0;
                            let dataArr = ctx.chart.data.datasets[0].data;
                            dataArr.map(data => {
                                sum += data;
                            });
                            let percentage = (value*100 / sum).toFixed(2)+"%";
                            return percentage;
                        },
                        color: '#fff',
                        display: false,
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'No. of Commodities',
                            font: {
                                size: 20,
                            },
                        },
                        grid: {
                            display: false
                        },
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
                    },
                },
            }
        },
        linechartOptions() {
            return {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        display: false,
                        onClick: (e, legendItem, legend) => {
                            // if a hidden legend is clicked, show all
                            if (legendItem.hidden)
                                legend.chart.data.datasets.forEach((dataset, i) => {
                                    dataset.hidden = false;
                                });
                            else
                                legend.chart.data.datasets.forEach((dataset, i) => {
                                    dataset.hidden = legendItem.text !== dataset.label;
                                });

                            legend.chart.update();
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                if (!tooltipItem.raw)
                                    return `There are no entries`;
                                else if (tooltipItem.raw > 1)
                                    return `Population of ${tooltipItem.raw}`;
                                return `Population of ${tooltipItem.raw}`;
                            }
                        }
                    },
                    datalabels: {
                        formatter: (value, ctx) => {
                            let sum = 0;
                            let dataArr = ctx.chart.data.datasets[0].data;
                            dataArr.map(data => {
                                sum += data;
                            });
                            let percentage = (value*100 / sum).toFixed(2)+"%";
                            return percentage;
                        },
                        color: '#fff',
                        display: false,
                    }
                },
                scales: {
                    x: {
                        beginAtZero: false,
                        title: {
                            display: true,
                            text: 'Population per variety',
                            font: {
                                size: 20,
                            },
                        },
                        grid: {
                            display: false
                        },
                    },
                    y: {
                        beginAtZero: false,
                        title: {
                            display: false,
                            text: this.filter,
                        },
                        grid: {
                            display: false
                        },
                    },
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
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                return `There are ${tooltipItem.raw} research studies of ${tooltipItem.label} in this slice`;
                            }
                        }
                    },
                    datalabels: {
                        formatter: (value, ctx) => {
                            let sum = 0;
                            let dataArr = ctx.chart.data.datasets[0].data;
                            dataArr.map(data => {
                                sum += data;
                            });
                            // percentage of the value and the name of the commodity
                            let percentage = (value*100 / sum).toFixed(0)+"%";
                            return `${percentage} ${ctx.chart.data.labels[ctx.dataIndex]}`;
                        },
                        color: '#fff',
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            text: 'No. of Commodities',
                            display: true,
                        },
                        grid: {
                            display: false
                        },
                        display: false
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            text: 'No. of Commodities',
                            display: false,
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
                commodity: this.filter.commodity,
            }).then(response => {
                this.apiResponse = response.data;
            });
        },
        updateFilters(key, value) {
            /*this.filter[key] = value;
            this.getSummary();*/

        },
        changeListOf(value) {
            // change the api url whether for breeders or commodities
            if (value === 'breeders') {
                this.api = new ApiService(route('api.breedersmap.breeders.summary.public'));
            } else {
                this.api = new ApiService(route('api.breedersmap.commodities.summary.public'));
            }
        },
        filterResponse(search)
        {
            console.log(this.apiResponse.commodities.filter(commodity => {
                // loop through the columns of the commodity
                if (search)
                for (let column in commodity) {
                    if (commodity[column] && commodity[column].toLowerCase().includes(search.toLowerCase()))
                        return commodity;
                }
            }));
        }
    }
}
</script>

<template>
    <div class="shadow-lg bg-gray-100 rounded-md sm:p-4 p-1 flex flex-col gap-2">
        <div>
            <div class="flex flex-col gap-1">
                <label>Color Opacity: {{colorOpacity}}</label>
                <input type="range" min="0.01" max="1.1" step="0.01" v-model="colorOpacity">
            </div>
            <collapsable-menu label="Filters" open-default>
                <custom-dropdown
                    label="Select a list"
                    :value="filter.table_name"
                    :withAllOption="false"
                    :options="[
                                    {label: 'Breeders', name: 'breeders'},
                                    {label: 'Commodity', name: 'commodities'},
                                 ]"
                    placeholder="Select a list"
                    @selectedChange="filter.table_name = $event; changeListOf($event); filter.search = null; getSummary($event);">
                    <template #icon>
                        <caret-down  class="h-4 w-4 text-gray-700" />
                    </template>
                </custom-dropdown>
                <custom-dropdown
                    v-if="data && data.commodity_labels && filter.table_name === 'commodities'"
                    label="Select a specific commodity"
                    searchable
                    :value="filter.commodity"
                    :withAllOption="false"
                    :options="data.commodity_labels.map(item => {
                                    return {label: item, name: item}
                                 })"
                    placeholder="None"
                    @selectedChange="filter.filter = 'name' ;filter.commodity = $event; getSummary($event)">
                    <template #icon>
                        <caret-down  class="h-4 w-4 text-gray-700" />
                    </template>
                </custom-dropdown>
                <custom-dropdown
                    label="Group by"
                    :value="filter.group_by"
                    :withAllOption="false"
                    :options="[
                                    {label: 'Region', name: 'region'},
                                    {label: 'Province', name: 'province'},
                                    {label: 'City', name: 'city'},
                                 ]"
                    placeholder="None"
                    @selectedChange="filter.group_by = $event; filter.search = null; getSummary($event);">
                    <template #icon>
                        <caret-down  class="h-4 w-4 text-gray-700" />
                    </template>
                </custom-dropdown>
                <custom-dropdown
                    v-if="data && data.group_search_labels"
                    searchable
                    :label="`Select a specific ${filter.group_by}`"
                    :value="filter.search"
                    :withAllOption="false"
                    :options="data.group_search_labels.map( item => {
                                    return {label: item, name: item}
                                 })"
                    placeholder="None"
                    @selectedChange="filter.search = $event; getSummary($event)">
                    <template #icon>
                        <caret-down  class="h-4 w-4 text-gray-700" />
                    </template>
                </custom-dropdown>
            </collapsable-menu>
            <div class="text-center py-2 my-3 rounded text-gray-700 text-2xl">
                {{ filter.commodity? `${filter.commodity} studies` : `List of ${filter.table_name }` }} {{ filter.search ? `in ${filter.search}` : ` grouped by ${filter.group_by}` }}
            </div>
            <div class="flex justify-evenly items-center my-5 gap-0.5">
                <div v-if="data.chart_data && !filter.search" class="flex justify-center" style="width: 100%; height: auto">
                    <Bar
                        id="my-chart-id"
                        :options="chartOptions"
                        :data="{
                    labels: data.chart_data.map(item => item.label),
                    datasets: [
                      {
                        label: 'By Region',
                        data: data.chart_data.map(item => item.total),
                        backgroundColor: listOfColors,
                        borderColor: listOfColors.map(color => color.replace('0.2', this.colorOpacity)),
                        borderWidth: 1,
                      },
                    ]
                  }"
                    />
                </div>
                <div v-if="data.commodities_chart && !filter.commodity" class="flex justify-center" style="width: 100%; height:auto">
                    <Doughnut
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
                <div v-if="data.commodities_linechart && filter.commodity" class="flex justify-center" style="width: 100%; height:auto">
                    <Line
                        :options="linechartOptions"
                        :data="{
                            labels: data.commodities_linechart.labels,
                            datasets: data.commodities_linechart.datasets
                        }"
                    />
                </div>
            </div>
            <div class="w-full flex flex-row gap-1 my-2">
                <search-box
                    :value="filter.search"
                    :options="{}"
                    :label="filter.search ?? 'Select a place'"
                    @searchString="updateFilters('search', $event)"
                    @keydown.enter="filterResponse($event.target.value)"
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
                        <tbody-row v-if="data.commodities.length" v-for="commodity in data.commodities">
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
            <div v-if="data.breeders" class="text-xs">
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
                        <tbody-row  v-if="data.breeders.length" v-for="breeder in data.breeders">
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
