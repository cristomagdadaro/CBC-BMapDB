<template>
    <div v-if="baseUrl === null || baseUrl === undefined || baseUrl === ''">
        Unable to to retrieve data, please check your base url.
    </div>
    <div v-else
        id="dtContainer"
         v-if="dt instanceof CRCMDatatable && dt.response['meta']"
         class="flex flex-col sm:gap-2 gap-1 bg-gray-200 px-2">
        <top-container>
            <filter-container>
                <per-page :value="dt.request.params.per_page" @changePerPage="dt.perPageFunc({ per_page: $event })" />
                <action-container>
                    <top-action-btn
                        @click="showAddDialogFunc()"
                        class="bg-add"
                        title="Add new data">
                        <template #icon>
                            <add-icon class="h-auto w-4" />
                        </template>
                        <span v-show="showIconText">Add</span>
                    </top-action-btn>
                    <top-action-btn
                        v-if="data.length"
                        class="bg-edit"
                        title="Modify existing data">
                        <template #icon>
                            <edit-icon class="h-auto w-4" />
                        </template>
                        <span v-show="showIconText">Edit</span>
                    </top-action-btn>
                    <top-action-btn
                        class="bg-refresh"
                        @click="dt.refresh()"
                        title="Refresh table">
                        <template #icon>
                            <refresh-icon class="h-auto w-4" :class="dt.processing?'animate-spin':'animate-none'" />
                        </template>
                        <span v-show="showIconText">Refresh</span>
                    </top-action-btn>
                    <top-action-btn
                        v-if="data.length && dt.selected.length"
                        class="bg-delete"
                        @click="showDeleteSelectedDialogFunc()"
                        title="Delete all the selected rows">
                        <template #icon>
                            <delete-icon class="h-auto w-4" />
                        </template>
                        <span v-show="showIconText">Delete Selected</span>
                    </top-action-btn>
                    <top-action-btn
                        v-if="data.length"
                        class="bg-select"
                        @click="dt.selectAll()"
                        title="Select all loaded rows">
                        <template #icon>
                            <checkall-icon class="h-auto w-4" />
                        </template>
                        <span v-show="showIconText">Select All</span>
                    </top-action-btn>
                    <top-action-btn
                        v-if="selected.length && data.length"
                        class="bg-deselect"
                        @click="dt.deselectAll()"
                        title="Deselect selected rows">
                        <template #icon>
                            <deselect-icon class="h-auto w-4" />
                        </template>
                        <span v-show="showIconText">Deselect All</span>
                    </top-action-btn>
                    <top-action-btn
                        v-if="data.length"
                        class="bg-export"
                        @click="dt.exportCSV()"
                        title="Export data into a CSV file">
                        <template #icon>
                            <export-icon class="h-auto w-4" />
                        </template>
                        <span v-show="showIconText">Export</span>
                    </top-action-btn>
                    <top-action-btn
                        class="bg-import"
                        @click="dt.importCSV()"
                        title="Import data from a CSV file">
                        <template #icon>
                            <import-icon class="h-auto w-4" />
                        </template>
                        <span v-show="showIconText">Import</span>
                    </top-action-btn>
                    <top-action-btn
                        class="bg-add"
                        @click="showIconText = !showIconText"
                        title="Toggle icon with text">
                        <template #icon>
                            <toggle-off-icon class="h-auto w-4" v-show="!showIconText" />
                            <toggle-on-icon class="h-auto w-4" v-show="showIconText" />
                        </template>
                    </top-action-btn>
                </action-container>
                <div class="flex flex-wrap items-center sm:w-fit w-full justify-between gap-2">
                    <search-by :value="dt.request.params.filter" :is-exact="dt.request.params.is_exact" :options="dt.columns" @isExact="dt.isExactFilter({ is_exact: $event })" @searchBy="dt.filterByColumn({ column: $event })" />
                    <search-filter :value="dt.request.params.search" @searchString="dt.searchFunc({ search: $event })" />
                </div>
            </filter-container>
        </top-container>
        <dialog-form-modal :show="showAddDialog" @close="closeDialog" >
            <create-breeder-form @submitForm="dt.create($event)" @close="closeDialog" />
        </dialog-form-modal>
        <dialog-modal :show="showEditDialog" @close="closeDialog">
            <template #title>
                Update Information
            </template>
            <template #content>
                <div class="text-sm text-gray-600">
                    Update information
                </div>
            </template>
            <template #footer>
                <button class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 active:bg-red-700 duration-200" @click="closeDialog">Update</button>
                <cancel-button @click="closeDialog">Cancel</cancel-button>
            </template>
        </dialog-modal>
        <dialog-modal :show="showDeleteDialog" @close="closeDialog">
            <template #title>
                Delete
            </template>
            <template #content>
                <div class="text-sm text-gray-600">
                    Are you sure you want to delete the item {{ toDeleteId }}?
                </div>
            </template>
            <template #footer>
                <danger-button @click="dt.delete(toDeleteId)">Delete</danger-button>
                <cancel-button @click="closeDialog">Cancel</cancel-button>
            </template>
        </dialog-modal>
        <dialog-modal :show="showDeleteSelectedDialog" @close="closeDialog">
            <template #title>
                Delete Multiple Rows
            </template>
            <template #content>
                <div class="text-sm text-gray-600">
                    Are you sure you want to delete the following items?
                    {{ dt.selected }}
                </div>
            </template>
            <template #footer>
                <danger-button @click="dt.deleteSelected()">
                    Delete all
                </danger-button>
                <cancel-button @click="closeDialog">Cancel</cancel-button>
            </template>
        </dialog-modal>
        <div id="dtTableContainer" class="flex relative w-full justify-center overflow-auto">
            <transition
                leave-active-class="transition ease-in duration-200"
                leave-from-class="transform opacity-100"
                leave-to-class="transform opacity-0">
                <div v-show="dt.processing" class="select-none flex justify-center w-full h-full absolute items-center gap-1 z-40 rounded bg-gray-700 text-gray-100">
                    <loader-icon class="h-5 w-5" />
                    Processing, please wait...
                </div>
            </transition>
            <table id="dtTable" class="w-full">
                <thead id="dtHeader">
                <tr class="dtHeaderRow">
                    <t-h :column="{name:'no', label:'&nbsp;'}" />
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
                           v-for="row in data"
                           :isSelected="dt.isSelected(row.id)"
                        >
                            <!-- Cell No. -->
                            <t-d class="text-xs text-gray-600 text-center flex items-center gap-0.5"
                                 @click="dt.addSelected(row.id)">
                                {{ meta_from + data.indexOf(row) }}
                                <input :checked="dt.isSelected(row.id)" type="checkbox" class="rounded"/>
                            </t-d>
                            <!-- Cell Data -->
                            <t-d
                                class="break-word align-text-top"
                                @click="dt.addSelected(row.id)"
                                v-for="cell in row" :key="cell">
                                {{ cell }}
                            </t-d>
                            <!-- Cell Actions -->
                            <t-d class="flex justify-center items-center">
                                <button @click="showDeleteDialogFunc(row.id)" class="bg-transparent rounded px-2 h-full hover:text-red-500 active:text-red-800 duration-200">
                                    <delete-icon class="h-auto w-5" />
                                </button>
                                <button @click="showEditDialogFunc(row.id)" class="text-yellow-500 hover:bg-yellow-200 rounded px-2 h-full hover:text-yellow-700 active:text-yellow-800 duration-200">
                                    <edit-icon class="h-auto w-5" />
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
            <selected-count :count="dt.selected.length" />
            <div id="dtPaginatorContainer" class="flex flex-wrap gap-0.5 items-center sca">
                <paginate-btn @click="dt.firstPage()" :disabled="current_page === first_page">First</paginate-btn>
                <paginate-btn @click="dt.prevPage()" :disabled="!prev_page"> <arrow-left class="h-auto w-6" />Prev</paginate-btn>
                <div class="text-xs flex flex-col text-center">
                    <span class="font-medium mx-1" title="current page and total pages">{{ current_page }} / {{ total_pages }}</span>
<!--                    <span class="font-medium mx-1">Page: <span>{{ current_page }}</span></span>
                    <span class="text-gray-500 mx-2">Total of {{ total_pages }} page<span v-if="total_pages > 1">s</span></span>-->
                </div>
                <paginate-btn @click="dt.nextPage()" :disabled="current_page === last_page">Next <arrow-right class="h-auto w-6" /></paginate-btn>
                <paginate-btn @click="dt.lastPage()" :disabled="current_page === last_page">Last</paginate-btn>
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
import Modal from "@/Components/Modal.vue";
import DialogModal from "@/Components/DialogModal.vue";
import DangerButton from "@/Components/CRCMDatatable/Components/DangerButton.vue";
import CancelButton from "@/Components/CRCMDatatable/Components/CancelButton.vue";
import TransitionContainer from "@/Components/CustomDropdown/Components/TransitionContainer.vue";
import ToggleOffIcon from "@/Components/Icons/ToggleOffIcon.vue";
import ToggleOnIcon from "@/Components/Icons/ToggleOnIcon.vue";
import TextField from "@/Components/Form/TextField.vue";
import TextInput from "@/Components/TextInput.vue";
import DialogFormModal from "@/Components/CRCMDatatable/Layouts/DialogFormModal.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import CreateBreederForm from "@/Pages/Projects/BreedersMap/CreateBreederForm.vue";
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
            type: [String, null],
            required: true,
        },
        baseModel: {
            type: Function,
            required: false,
        },
    },
    data() {
        return {
            dt: null,
            showModal: false,
            showDeleteSelectedDialog: false,
            showDeleteDialog: false,
            toDeleteId: null,
            showEditDialog: false,
            toEditId: null,
            showAddDialog: false,
            showIconText: false ,
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
        first_page() {
            return 1;
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
    methods: {
        showAddDialogFunc(){
            this.showModal = true;
            this.showAddDialog = true;
        },
        showDeleteDialogFunc(id) {
            this.showModal = true;
            this.showDeleteDialog = true;
            this.toDeleteId = id;
        },
        showEditDialogFunc(id) {
            this.showModal = true;
            this.showEditDialog = true;
            this.toEditId = id;
        },
        showDeleteSelectedDialogFunc() {
            this.showModal = true;
            this.showDeleteSelectedDialog = true;
        },
        closeDialog() {
            this.showModal = false;
            this.showDeleteDialog = false;
            this.showEditDialog = false;
            this.showAddDialog = false;
            this.showDeleteSelectedDialog = false;

            this.toDeleteId = null;
            this.toEditId = null;
        },
        initializeDatatable() {
            this.dt = new CRCMDatatable(this.baseUrl, this.baseModel);
            this.dt.init();
        },
    },
    mounted() {
        if (this.baseUrl){
            this.initializeDatatable();
        }
    },
};
</script>

