<template>
    <div v-if="baseUrl === null || baseUrl === undefined || baseUrl === ''">
        Unable to to retrieve data, please check your base url.
    </div>
    <div v-else-if="!canView">
        You don't have permission to view this data.
    </div>
    <div v-else
        id="dtContainer"
         v-if="dt instanceof CRCMDatatable"
         class="flex flex-col sm:gap-2 gap-1 bg-transparent sm:p-3 p-1 overflow-x-auto">
        <top-container>
            <div class="flex justify-between gap-2">
                <per-page class="sm:w-1/3 w-full" :value="dt.request.params.per_page" @changePerPage="dt.perPageFunc({ per_page: $event })" />
                <action-container class="w-full">
                    <top-action-btn
                        v-if="showActionBtns && canCreate"
                        @click="showAddDialogFunc()"
                        class="bg-add"
                        title="Add new data">
                        <template #icon>
                            <add-icon class="h-auto sm:w-6 w-4" />
                        </template>
                        <span v-show="showIconText">Add</span>
                    </top-action-btn>
                    <top-action-btn
                        :class="dt.processing? 'bg-gray-300 text-gray-800': 'bg-refresh'"
                        @click="dt.refresh()"
                        title="Refresh table">
                        <template #icon>
                            <refresh-icon class="h-auto sm:w-6 w-4" :class="dt.processing?'animate-spin':'animate-none'" />
                        </template>
                        <span v-show="showIconText">Refresh</span>
                    </top-action-btn>
                    <top-action-btn
                        v-if="canDelete && data.length && dt.selected.length && showActionBtns"
                        class="bg-delete"
                        @click="showDeleteSelectedDialogFunc()"
                        title="Delete all the selected rows">
                        <template #icon>
                            <delete-icon class="h-auto sm:w-6 w-4" />
                        </template>
                        <span v-show="showIconText">Delete Selected</span>
                    </top-action-btn>
                    <top-action-btn
                        v-if="data.length && showActionBtns"
                        class="bg-select"
                        @click="dt.selectAll()"
                        :topText="dt.selected.length"
                        title="Select all loaded rows">
                        <template #icon>
                            <checkall-icon class="h-auto sm:w-6 w-4" />
                        </template>
                        <span v-show="showIconText">Select All</span>
                    </top-action-btn>
                    <top-action-btn
                        v-if="selected.length && data.length && showActionBtns"
                        class="bg-deselect"
                        @click="dt.deselectAll()"
                        title="Deselect selected rows">
                        <template #icon>
                            <deselect-icon class="h-auto sm:w-6 w-4" />
                        </template>
                        <span v-show="showIconText">Deselect All</span>
                    </top-action-btn>
                    <top-action-btn
                        v-if="data.length"
                        class="bg-export"
                        @click="dt.exportCSV()"
                        title="Export data into a CSV file">
                        <template #icon>
                            <export-icon class="h-auto sm:w-6 w-4" />
                        </template>
                        <span v-show="showIconText">Export</span>
                    </top-action-btn>
                    <top-action-btn
                        v-if="showActionBtns"
                        class="bg-import"
                        @click="showImportModal = true"
                        title="Import data from a CSV file">
                        <template #icon>
                            <import-icon class="h-auto sm:w-6 w-4" />
                        </template>
                        <span v-show="showIconText">Import</span>
                    </top-action-btn>
                    <top-action-btn
                        class="bg-add"
                        @click="toggleShowIconText"
                        title="Toggle icon with text">
                        <template #icon>
                            <toggle-off-icon class="h-auto sm:w-6 w-4" v-show="!showIconText" />
                            <toggle-on-icon class="h-auto sm:w-6 w-4" v-show="showIconText" />
                        </template>
                    </top-action-btn>
                </action-container>
                <div id="dtPaginatorContainer" class="flex gap-1 items-center">
                    <paginate-btn @click="dt.firstPage()" :disabled="current_page === first_page">First</paginate-btn>
                    <paginate-btn @click="dt.prevPage()" :disabled="!prev_page"> <arrow-left class="h-auto w-6" />Prev</paginate-btn>
                    <div class="text-xs flex flex-col whitespace-nowrap text-center">
                        <span class="font-medium mx-1" title="current page and total pages">
                        <input
                            ref="input"
                            type="text"
                            :value="current_page"
                            class="border-x-0 text-right border-t-0 border-b p-0"
                            :style="{ width: inputWidth + 'px' }"
                            @keydown.enter="updateWidth"
                        /> / {{ total_pages }}
                        </span>
                    </div>
                    <paginate-btn @click="dt.nextPage()" :disabled="current_page === last_page">Next <arrow-right class="h-auto w-6" /></paginate-btn>
                    <paginate-btn @click="dt.lastPage()" :disabled="current_page === last_page">Last</paginate-btn>
                </div>
                <div class="flex flex-row items-center sm:w-1/3 w-full sm:justify-end justify-between gap-2">
                    <search-by :value="dt.request.params.filter" :is-exact="dt.request.params.is_exact" :options="dt.columns" @isExact="dt.isExactFilter({ is_exact: $event })" @searchBy="dt.filterByColumn({ column: $event })" />
                    <search-filter :value="dt.request.params.search" @searchString="dt.searchFunc({ search: $event })" />
                </div>
            </div>
            <div id="dtFooterContainer" class="flex flex-wrap-reverse items-center sm:justify-between justify-center gap-5 select-none" v-if="dt.response instanceof BaseResponse">
        </div>

        </top-container>
        <div id="dtTableContainer" class="flex relative w-full justify-center overflow-x-auto">
            <transition
                leave-active-class="transition ease-in duration-200"
                leave-from-class="transform opacity-100"
                leave-to-class="transform opacity-0">
                <div v-show="dt.processing" class="select-none flex justify-center w-full h-full absolute items-center gap-1 z-40 rounded bg-gray-700 text-gray-100">
                    <loader-icon class="h-5 w-5" />
                    Processing, please wait...
                </div>
            </transition>
            <div class="overflow-x-auto w-full">
                <crcm-table id="dtTable">
                    <crcm-thead>
                        <thead-row>
                            <t-h column="&nbsp;" />
                            <t-h
                                v-for="column in dt.model.getColumns()"
                                :visible="column.visible"
                                :sortable="column.sortable"
                                :key="column.key"
                                :sortedValue="column.key === dt.request.getParam('sort')"
                                :column="column.title"
                                :order="dt.request.getParam('order')"
                                :class="column.sortable?'cursor-pointer':'cursor-auto'"
                                @click="column.sortable && dt.sortFunc({ sort: column.key })"
                            />
                            <t-h
                                v-if="showActionBtns"
                                column="Action"
                            />
                        </thead-row>
                    </crcm-thead>
                    <crcm-tbody>
                        <template v-if="dt.processing">
                            <processing-row :colspan="dt.columns.length" />
                        </template>
                        <template v-else>
                            <tbody-row v-if="data.length && !dt.processing"
                                       v-for="row in data"
                                       :isSelected="dt.isSelected(row.id)"
                            >
                                <!-- Cell No. -->
                                <t-d class="text-xs text-gray-600 items-center">
                                    <div class="flex gap-1">
                                        {{ meta_from + data.indexOf(row) }}
                                        <input @click="dt.addSelected(row.id)" :checked="dt.isSelected(row.id)" type="checkbox" class="rounded focus:ring-transparent active:ring-transparent"/>
                                    </div>
                                </t-d>
                                <!-- Cell Data -->
                                <template v-for="(value, label) in row" :key="value">
                                    <t-d
                                        class="break-words text-sm border border-gray-300"
                                        v-on:dblclick="dt.addSelected(row.id)"
                                        v-on:click.ctrl="dt.addSelected(row.id)"
                                        v-if="dataValue(label)">
                                        {{ value }}
                                    </t-d>
                                </template>
                                <!-- Cell Actions -->
                                <t-d class="items-center" v-if="showActionBtns">
                                    <div class="flex justify-center sm:gap-1 gap-0.5">
                                        <top-action-btn
                                            v-if="canView && viewForm"
                                            @click="showViewDialogFunc(row.id)"
                                            class="bg-view"
                                            title="View">
                                            <template #icon>
                                                <view-icon class="h-auto sm:w-4 w-3" />
                                            </template>
                                            <span v-show="showIconText">View</span>
                                        </top-action-btn>
                                        <top-action-btn
                                            v-if="canUpdate"
                                            @click="showEditDialogFunc(row.id)"
                                            class="bg-edit"
                                            title="Modify this row">
                                            <template #icon>
                                                <edit-icon class="h-auto sm:w-4 w-3" />
                                            </template>
                                            <span v-show="showIconText">Edit</span>
                                        </top-action-btn>
                                        <top-action-btn
                                            v-if="canDelete"
                                            @click="showDeleteDialogFunc(row.id)"
                                            class="bg-delete"
                                            title="Delete this row">
                                            <template #icon>
                                                <delete-icon class="h-auto sm:w-4 w-3" />
                                            </template>
                                            <span v-show="showIconText">Delete</span>
                                        </top-action-btn>
                                    </div>
                                </t-d>
                            </tbody-row>
                            <not-found-row v-else :colspan="dt.model.getColumns().length+2" />
                        </template>
                    </crcm-tbody>
                </crcm-table>
                <div class="flex w-full justify-between p-2">
                    <div id="dtPageDetails">
                        Showing {{ meta_from }} to {{ meta_to }} of {{ total_entries }} entries
                    </div>
                    <selected-count :count="dt.selected.length" />
                </div>
            </div>
        </div>
        <dialog-form-modal :show="showImportModal && canCreate" @close="closeDialog">
            <component :is="importModal" v-if="importModal" :errors="dt.errorBag" @uploadForm="dt.importCSV($event)" @close="closeDialog" :forceClose="dt.closeAllModal"/>
        </dialog-form-modal>
        <dialog-form-modal :show="showAddDialog && canCreate" @close="closeDialog">
            <component :is="addForm" v-if="addForm" :errors="dt.errorBag" @submitForm="dt.create($event)" @close="closeDialog" :forceClose="dt.closeAllModal"/>
        </dialog-form-modal>
        <dialog-form-modal :show="showEditDialog && canUpdate" @close="closeDialog">
            <component :is="editForm" v-if="editForm" :errors="dt.errorBag" @submitForm="dt.update($event)" @close="closeDialog" :forceClose="dt.closeAllModal" :data="toEditData"/>
        </dialog-form-modal>
        <dialog-modal :show="showDeleteDialog && canDelete" @close="closeDialog" :processing="dt.processing" :forceClose="dt.closeAllModal">
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
        <dialog-modal :show="showDeleteSelectedDialog && canDelete" @close="closeDialog" :processing="dt.processing" :forceClose="dt.closeAllModal">
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
    </div>
</template>
<script setup>
import BaseResponse from "@/Modules/core/infrastructure/BaseResponse.js";
import TopActionBtn from "@/Components/CRCMDatatable/Components/TopActionBtn.vue";
import PaginateBtn from "@/Components/CRCMDatatable/Components/PaginateBtn.vue";
import ActionContainer from "@/Components/CRCMDatatable/Layouts/ActionContainer.vue";
import TopContainer from "@/Components/CRCMDatatable/Layouts/TopContainer.vue";
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
import SearchBy from "@/Components/CRCMDatatable/Components/SearchBy.vue";
import SelectedCount from "@/Components/CRCMDatatable/Components/SelectedCount.vue";
import DeleteIcon from "@/Components/Icons/DeleteIcon.vue";
import AddIcon from "@/Components/Icons/AddIcon.vue";
import RefreshIcon from "@/Components/Icons/RefreshIcon.vue";
import EditIcon from "@/Components/Icons/EditIcon.vue";
import ExportIcon from "@/Components/Icons/ExportIcon.vue";
import ImportIcon from "@/Components/Icons/ImportIcon.vue";
import CheckallIcon from "@/Components/Icons/CheckallIcon.vue";
import DeselectIcon from "@/Components/Icons/DeselectIcon.vue";
import selectedCount from "@/Components/CRCMDatatable/Components/SelectedCount.vue";
import DialogModal from "@/Components/DialogModal.vue";
import DangerButton from "@/Components/CRCMDatatable/Components/DangerButton.vue";
import CancelButton from "@/Components/CRCMDatatable/Components/CancelButton.vue";
import ToggleOffIcon from "@/Components/Icons/ToggleOffIcon.vue";
import ToggleOnIcon from "@/Components/Icons/ToggleOnIcon.vue";
import DialogFormModal from "@/Components/CRCMDatatable/Layouts/DialogFormModal.vue";
import CrcmTable from "@/Components/CRCMDatatable/Components/CrcmTable.vue";
import CrcmThead from "@/Components/CRCMDatatable/Components/CrcmThead.vue";
import TheadRow from "@/Components/CRCMDatatable/Components/TheadRow.vue";
import CrcmTbody from "@/Components/CRCMDatatable/Components/CrcmTbody.vue";
import ViewIcon from "@/Components/Icons/ViewIcon.vue";
</script>

<script>
import CRCMDatatable from "@/Modules/core/components/CRCMDatatable.js";
import { router } from "@inertiajs/vue3";
import {defineAsyncComponent} from "vue";
import ApiService from "@/Modules/core/infrastructure/ApiService.js";

export default {
    name: "CRCMDatatable",
    props: {
        baseUrl: {
            type: [String, null],
            required: true,
        },
        baseModel: {
            type: [Object, Function],
            required: false,
        },
        importModal: {
            type: [Object, Function],
            required: false,
            default: defineAsyncComponent({
                loader: () => import("@/Components/CRCMDatatable/Layouts/DefaultBlankForm.vue"),
            }),
        },
        addForm: {
            type: [Object, Function],
            required: false,
            default: defineAsyncComponent({
                loader: () => import("@/Components/CRCMDatatable/Layouts/DefaultBlankForm.vue"),
            })
        },
        editForm: {
            type: [Object, Function],
            required: false,
            default: defineAsyncComponent({
                loader: () => import("@/Components/CRCMDatatable/Layouts/DefaultBlankForm.vue"),
            }),
        },
        viewForm: {
            type: [String, Object, Function],
            required: false,
            default: null,
        },
        showActionBtns: {
            type: Boolean,
            required: false,
            default: true,
        },
        canCreate: {
            type: Boolean,
            required: false,
            default: false,
        },
        canUpdate: {
            type: Boolean,
            required: false,
            default: false,
        },
        canDelete: {
            type: Boolean,
            required: false,
            default: false,
        },
        canView: {
            type: Boolean,
            required: false,
            default: false,
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
            toEditData: null,
            showAddDialog: false,
            showImportModal: false,
            inputWidth: 1
        }
    },
    computed: {
        data() {
            if (this.checkIfDataIsLoaded)
                return this.dt.response['data'];
            return [];
        },
        selected(){
            return this.dt.selected;
        },
        current_page() {
            if (this.checkIfDataIsLoaded)
                return this.dt.response['meta']['current_page'];
            return 0;
        },
        last_page() {
            if (this.checkIfDataIsLoaded)
                return this.dt.response['meta']['last_page'];
            return 0;
        },
        next_page() {
            if (this.checkIfDataIsLoaded)
                return this.dt.response['meta']['current_page'] + 1;
            return 0;
        },
        prev_page() {
            if (this.checkIfDataIsLoaded)
                return this.dt.response['meta']['current_page'] - 1;
            return 0;
        },
        first_page() {
            return 0;
        },
        total_pages() {
            if (this.checkIfDataIsLoaded)
                return this.dt.response['meta']['last_page'];
            return 0;
        },
        total_entries() {
            if (this.checkIfDataIsLoaded)
                return this.dt.response['meta']['total'];
            return 0;
        },
        meta_from() {
            if (this.checkIfDataIsLoaded)
                return this.dt.response['meta']['from'];
            return 0;
        },
        meta_to() {
            if (this.checkIfDataIsLoaded)
                return this.dt.response['meta']['to'];
            return 0;
        },
        showIconText() {
            return this.$store.state.showTextWithIcon;
        },
        checkIfDataIsLoaded() {
            return !!this.dt.response && !!this.dt.response['meta'] && !!this.dt.response['data'];
        }
    },
    methods: {
        dataValue(label) {
            try {
                return Array.from(this.dt.model.getColumns()).find(col => col.key === label).visible && true
            } catch (e) {
                return false;
            }
        },
        toggleShowIconText() {
            this.$store.dispatch("asyncToggleShowTextWithIcon");
        },
        showAddDialogFunc(){
            this.showModal = true;
            this.showAddDialog = true;
        },
        showDeleteDialogFunc(id) {
            this.showModal = true;
            this.showDeleteDialog = true;
            this.toDeleteId = id;
        },
        showViewDialogFunc(id) {
            if (typeof this.viewForm == "object"){
                console.log("Object is passed, not yet implemented. Try passing a route name.");
            }
            else if (typeof this.viewForm == "string") {
                router.get(route(this.viewForm, id));
            } else {
                alert("None is given");
            }
        },
        async showEditDialogFunc(id) {
            this.showModal = true;
            this.showEditDialog = true;
            this.toEditData = (await new ApiService(this.baseUrl).get({
                filter: 'id',
                search: id,
                is_exact: true,
            }, this.baseModel)).data[0];
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
            this.showImportModal = false;
            this.showDeleteSelectedDialog = false;
            this.dt.closeAllModal = false;
            this.dt.errorBag = {};

            this.toDeleteId = null;
            this.toEditId = null;
        },
        async initializeDatatable() {
            this.dt = new CRCMDatatable(this.baseUrl, this.baseModel);
            await this.dt.init();
        },
        updateWidth() {
            this.$nextTick(() => {
                const input = this.$refs.input;
                if (input) {
                    this.inputWidth = input.scrollWidth;
                }
            });
            if (this.$refs.input && this.$refs.input.value > 0 && this.$refs.input.value <= this.total_pages)
                this.dt.gotoPage(this.$refs.input.value);
            else
                this.dt.gotoPage(this.total_pages);
        }
    },
    async mounted() {
        if (this.baseUrl){
            await this.initializeDatatable();
        }

        this.updateWidth();
    },
    setup() {
        return {
            CRCMDatatable,
        }
    }
};
</script>

