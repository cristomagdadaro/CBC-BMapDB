<template>
    <div v-if="baseUrl === null || baseUrl === undefined">
        Unable to to retrieve data, please check your base url.
    </div>
    <div id="dtContainer" class="flex flex-col gap-1 bg-gray-200 px-2" v-if="dt instanceof CRCMDatatable && dt.response['meta']">
        <top-container>
            <action-container>
                <top-action-btn class="bg-add">
                    <add-icon class="h-auto w-4" />
                    Add
                </top-action-btn>
                <top-action-btn
                    v-if="data.length"
                    class="bg-edit">
                    <edit-icon class="h-auto w-4" />
                    Edit
                </top-action-btn>
                <top-action-btn
                    v-if="data.length"
                    class="bg-delete">
                    <delete-icon class="h-auto w-4" />
                    Delete
                </top-action-btn>
                <top-action-btn class="bg-refresh" @click="dt.refresh()">
                    <refresh-icon class="h-auto w-4" :class="dt.processing?'animate-spin':'animate-none'" />
                    Refresh
                </top-action-btn>
                <top-action-btn v-if="data.length" class="bg-select" @click="dt.selectAll()">
                    <checkall-icon class="h-auto w-4" />
                    Select All
                </top-action-btn>
                <top-action-btn
                    v-if="selected.length && data.length"
                    class="bg-deselect"
                    @click="dt.deselectAll()">
                    <deselect-icon class="h-auto w-4" />
                    Deselect All
                </top-action-btn>
                <top-action-btn
                    v-if="data.length"
                    class="bg-export"
                    @click="dt.exportCSV()">
                    <export-icon class="h-auto w-4" />
                    Export
                </top-action-btn>
                <top-action-btn class="bg-import" @click="dt.importCSV()">
                    <import-icon class="h-auto w-4" />
                    Import
                </top-action-btn>
            </action-container>
        </top-container>
        <filter-container>
            <per-page :value="dt.request.params.per_page" @changePerPage="dt.perPageFunc({ per_page: $event })" />
            <selected-count :count="dt.selected.length" />
            <div class="flex gap-2">
                <search-by :value="dt.request.params.filter" :is-exact="dt.request.params.is_exact" :options="dt.columns" @isExact="dt.isExactFilter({ is_exact: $event })" @searchBy="dt.filterByColumn({ column: $event })" />
                <search-filter :value="dt.request.params.search" @searchString="dt.searchFunc({ search: $event })" />
            </div>
        </filter-container>
        <div id="dtTableContainer" class="flex w-full justify-center select-none">
            <table id="dtTable" class="w-full">
                <thead id="dtHeader">
                <tr class="dtHeaderRow">
                    <t-h
                        v-for="column in dt.columns"
                        :sortable="column.sortable"
                        :key="column.id"
                        :column="column"
                        @click="dt.sortFunc({ sort: column.name })"
                        :order="dt.request.getParam('order')"
                        :sorted-column="dt.request.getParam('sort')"
                    />
                    <t-h
                        :column="{name:'action', label:'Action'}"
                    />
                </tr>
                </thead>
                <tbody id="dtBody">
                    <template v-if="dt.processing">
                        <processing-row :colspan="dt.columns.length" />
                    </template>
                    <template v-else>
                        <tbody-row v-if="data.length && !dt.processing"
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
                Showing {{ meta_from }} to {{ meta_to }} of {{ total_entries }} entries
            </div>
            <div id="dtPaginatorContainer" class="flex gap-0.5 items-center sca">

                <paginate-btn @click="dt.firstPage()">First</paginate-btn>
                <paginate-btn @click="dt.prevPage()"> <arrow-left class="h-auto w-6" />Prev</paginate-btn>
                <div class="text-xs flex flex-col text-center">
                    <span class="font-medium mx-1">Page: <span>{{ current_page }}</span></span>
                    <span class="text-gray-500 mx-2">Total of {{ total_pages }} page<span v-if="total_pages > 1">s</span></span>
                </div>
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
import AddIcon from "@/Components/Icons/AddIcon.vue";
import RefreshIcon from "@/Components/Icons/RefreshIcon.vue";
import EditIcon from "@/Components/Icons/EditIcon.vue";
import ExportIcon from "@/Components/Icons/ExportIcon.vue";
import ImportIcon from "@/Components/Icons/ImportIcon.vue";
import CheckallIcon from "@/Components/Icons/CheckallIcon.vue";
import DeselectIcon from "@/Components/Icons/DeselectIcon.vue";
import selectedCount from "@/Components/CRCMDatatable/Components/SelectedCount.vue";
import BaseRequest from "@/Modules/core/infrastructure/BaseRequest.js";
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
    computed: {
        data() {
            return this.dt.response['data'];
        },
        selected(){
            return this.dt.selected;
        },
        current_page() {
            return this.dt.response['meta']['current_page'];
        },
        last_page() {
            return this.dt.response['meta']['last_page'];
        },
        next_page() {
            return this.dt.response['meta']['current_page'] + 1;
        },
        prev_page() {
            return this.dt.response['meta']['current_page'] - 1;
        },
        total_pages() {
            return this.dt.response['meta']['last_page'];
        },
        total_entries() {
            return this.dt.response['meta']['total'];
        },
        meta_from() {
            return this.dt.response['meta']['from'];
        },
        meta_to() {
            return this.dt.response['meta']['to'];
        },
    },
    mounted() {
        this.dt = new CRCMDatatable(this.baseUrl, this.model);
        this.dt.init();
    }
};
</script>

