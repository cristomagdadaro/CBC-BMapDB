<template>
    <div v-if="baseUrl === null || baseUrl === undefined">
        Unable to to retrieve data, please check your base url.
    </div>
    <div id="dtContainer" class="flex flex-col gap-1 bg-gray-200 px-2" v-if="dt instanceof CRCMDatatable && dt.response['meta']">
        <top-container>
            <action-container>
                <top-action-btn>Add</top-action-btn>
                <top-action-btn>Edit</top-action-btn>
                <top-action-btn>Delete</top-action-btn>
                <top-action-btn @click="dt.refresh()">Refresh</top-action-btn>
                <top-action-btn @click="dt.exportCSV()">Export</top-action-btn>
                <top-action-btn @click="dt.importCSV()">Import</top-action-btn>
                <top-action-btn @click="dt.selectAll()">Select All</top-action-btn>
                <top-action-btn @click="dt.deselectAll()">Deselect All</top-action-btn>
            </action-container>
        </top-container>
        <filter-container>
            <per-page @changePerPage="dt.perPageFunc({ per_page: $event })" />
            <selected-count :count="dt.selected.length" />
            <div class="flex gap-2">
                <search-by :columns="dt.columns" @isExact="dt.isExactFilter({ is_exact: $event })" @searchBy="dt.filterByColumn({ column: $event })" />
                <search-filter @searchString="dt.searchFunc({ search: $event })" />
            </div>
        </filter-container>
        <div id="dtTableContainer" class="flex w-full justify-center select-none">
            <table id="dtTable" class="w-full">
                <thead id="dtHeader">
                <tr class="dtHeaderRow">
                    <t-h v-for="column in dt.columns" :key="column.id" @click="dt.sortFunc({ sort: column.name })" :order="dt.request.getParam('order')">
                        {{ column.label }}
                    </t-h>
                    <t-h> Actions </t-h>
                </tr>
                </thead>
                <tbody id="dtBody">
                <processing-row v-if="dt.processing" :colspan="dt.columns.length" />
                <template v-else>
                    <tbody-row v-if="dt.response['data'].length && !dt.processing"
                               v-for="row in dt.response['data']"
                               @click="dt.addSelected(row.id)"
                               :isSelected="dt.isSelected(row.id)"
                    >
                        <!-- Cell Data -->
                        <t-d v-for="cell in row" :key="cell">
                            <input v-if="cell === row.id" :checked="dt.isSelected(row.id)" type="checkbox" :value="cell" />
                            {{ cell }}
                        </t-d>
                        <!-- Cell Actions -->
                        <t-d>
                            <button @click="dt.delete(row.id)" class="bg-transparent rounded px-2 h-full hover:text-red-500 active:text-red-800 duration-200">
                                <delete-icon class="h-auto w-5" />
                            </button>
                        </t-d>
                    </tbody-row>
                    <not-found-row v-else :colspan="dt.columns.length" />
                </template>
                </tbody>
            </table>
        </div>
        <div id="dtFooterContainer" class="flex justify-between gap-5 select-none" v-if="dt.response instanceof BaseResponse">
            <div id="dtPageDetails">
                Showing {{ dt.response['meta']['from'] }} to {{ dt.response['meta']['to'] }} of {{ dt.response['meta']['total'] }} entries
            </div>
            <div id="dtPaginatorContainer" class="flex gap-0.5 items-center sca">
                <paginate-btn @click="dt.firstPage()">First</paginate-btn>
                <paginate-btn :disabled="true" @click="dt.prevPage()"> <arrow-left class="h-auto w-6" />Prev</paginate-btn>
                <span class="text-sm mx-1">Page: <span>{{ dt.response['meta']['current_page'] }}</span></span>
                <paginate-btn @click="dt.nextPage()">Next <arrow-right class="h-auto w-6" /></paginate-btn>
                <paginate-btn @click="dt.lastPage()">Last</paginate-btn>
            </div>
        </div>
    </div>
</template>
<script setup>
import Dropdown from "@/Components/CustomDropdown/CustomDropdown.vue";
import BaseResponse from "@/Modules/core/infrastructure/BaseResponse.js";
import TopActionBtn from "@/Components/CRCMDatatable/Components/TopActionBtn.vue";
import PaginateBtn from "@/Components/CRCMDatatable/Components/PaginateBtn.vue";
import ActionContainer from "@/Components/CRCMDatatable/Layouts/ActionContainer.vue";
import TopContainer from "@/Components/CRCMDatatable/Layouts/TopContainer.vue";
import FilterContainer from "@/Components/CRCMDatatable/Layouts/FilterContainer.vue";
import LoaderIcon from "@/Components/Icons/LoaderIcon.vue";
import PerPage from "@/Components/CRCMDatatable/Components/PerPage.vue";
import SearchFilter from "@/Components/CRCMDatatable/Components/SearchBox.vue";
import TH from "@/Components/CRCMDatatable/Components/TH.vue";
import TD from "@/Components/CRCMDatatable/Components/TD.vue";
import ProcessingRow from "@/Components/CRCMDatatable/Components/ProcessingRow.vue";
import TbodyRow from "@/Components/CRCMDatatable/Components/TbodyRow.vue";
import NotFoundRow from "@/Components/CRCMDatatable/Components/NotFoundRow.vue";
import ArrowLeft from "@/Components/Icons/ArrowLeft.vue";
import ArrowRight from "@/Components/Icons/ArrowRight.vue";
import CircleOneIcon from "@/Components/Icons/CircleOneIcon.vue";
import SearchBy from "@/Components/CRCMDatatable/Components/SearchBy.vue";
import SelectedCount from "@/Components/CRCMDatatable/Components/SelectedCount.vue";
import DeleteIcon from "@/Components/Icons/DeleteIcon.vue";
import CustomDropdown from "@/Components/CustomDropdown/CustomDropdown.vue";
</script>

<script>
import CRCMDatatable from "@/Modules/core/components/CRCMDatatable.js";
export default {
    name: "CRCMDatatable",
    components: {
        CRCMDatatable,
    },
    props: {
        baseUrl: {
            type: String,
            required: true,
        },
        model: {
            type: Object,
            required: false,
        },
    },
    data() {
        return {
            dt: null,
        }
    },
    mounted() {
        this.dt = new CRCMDatatable(this.baseUrl, this.model);
        this.dt.init();
    }
};
</script>


<style>
.asc::after {
    content: '▲';
}

.desc::after {
    content: '▼';
}
</style>


