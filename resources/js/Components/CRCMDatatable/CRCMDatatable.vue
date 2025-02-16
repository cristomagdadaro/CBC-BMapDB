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
         class="flex flex-col sm:gap-2 gap-1 bg-transparent sm:p-3 p-1 overflow-x-hidden">
        <top-container>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 justify-between gap-2">
                <div class="flex flex-row items-center w-full sm:justify-end justify-between gap-2">
                    <per-page :value="dt.request.getPerPage" @changePerPage="dt.perPageFunc({ per_page: $event })" />
                    <search-by :value="dt.request.getFilter" :is-exact="dt.request.getIsExact" :options="dt.columns" @isExact="dt.isExactFilter({ is_exact: $event })" @searchBy="dt.filterByColumn({ column: $event })" />
                    <search-filter :value="dt.request.getSearch" @searchString="dt.searchFunc({ search: $event })" class="w-full" />
                </div>
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
                        <span v-show="showIconText">Delete</span>
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
                        v-if="data.length && showActionBtns && canView"
                        class="bg-export"
                        @click="dt.exportCSV()"
                        title="Export data into a CSV file">
                        <template #icon>
                            <export-icon class="h-auto sm:w-6 w-4" />
                        </template>
                        <span v-show="showIconText">Export</span>
                    </top-action-btn>
                    <top-action-btn
                        v-if="showActionBtns && canCreate"
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
                <div id="dtPaginatorContainer" class="flex gap-1 items-center w-full justify-center">
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
            </div>
        </top-container>
        <div id="dtTableContainer" class="flex relative w-full justify-center overflow-x-hidden">
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
                                :key="column.key + column.title"
                                :filteredValues="dt.request.getFilter === column.db_key"
                                :sortedValue="!!clickSortCtr && column.key === dt.request.getSort"
                                :column="column.title"
                                :order="clickSortCtr ? dt.request.getParam('order') : null"
                                :class="column.sortable?'cursor-pointer':'cursor-auto'"
                                @click="onColumnSort(column)"
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
                                       @contextmenu="showContextMenu($event, row)"
                            >
                                <!-- Cell No. -->
                                <t-d class="text-normal text-gray-600 items-center">
                                    <div class="flex gap-1">
                                        {{ meta_from + data.indexOf(row) }}
                                        <input @click="dt.addSelected(row.id)" :checked="dt.isSelected(row.id)" type="checkbox" class="rounded focus:ring-transparent active:ring-transparent"/>
                                    </div>
                                </t-d>
                                <!-- Cell Data -->
                                <t-d v-for="column in visibleColumns" :key="column.key + column.title"
                                    class="break-words border border-gray-300 text-normal p-2"
                                    :class="column.align"
                                    @dblclick="dt.addSelected(row.id)"
                                    @click.ctrl="dt.addSelected(row.id)">
                                    {{ getNestedValue(row, column.key)  }}
                                </t-d>
                                <!-- Cell Actions -->
                                <t-d class="items-center" v-if="showActionBtns">
                                    <div class="flex justify-center sm:gap-1 gap-0.5">
                                        <Link
                                            v-if="canView && viewForm && route().has(viewForm)"
                                            class="bg-view rounded p-0"
                                            title="View"
                                            :href="route(viewForm, row.id)"
                                        >
                                            <top-action-btn
                                                class="bg-view"
                                                title="View">
                                                <template #icon>
                                                    <view-icon class="h-auto sm:w-4 w-3" />
                                                </template>
                                                <span v-show="showIconText">View</span>
                                            </top-action-btn>
                                        </Link>

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
                                    <context-menu ref="contextMenu" v-if="rowContextMenu">
                                        <div class="flex flex-col justify-center sm:gap-1 gap-0.5">
                                            <Link
                                                v-if="canView && viewForm && route().has(viewForm)"
                                                title="View"
                                                class="flex gap-1 p-1 items-center hover:bg-gray-200 cursor-pointer"
                                                :href="route(viewForm, rowContextMenu.id)"
                                            >
                                                <view-icon class="h-auto sm:w-5 w-4 p-0.5 text-view" />
                                                <span>View</span>
                                            </Link>

                                            <button
                                                v-if="canUpdate"
                                                @click="showEditDialogFunc(rowContextMenu.id)"
                                                title="Modify this row"
                                                class="flex gap-1 p-1 items-center hover:bg-gray-200"
                                            >
                                                <edit-icon class="h-auto sm:w-5 w-4 p-0.5 text-edit" />
                                                <span>Update</span>
                                            </button>
                                            <div
                                                v-if="canDelete"
                                                @click="showDeleteDialogFunc(rowContextMenu.id)"
                                                title="Delete this row"
                                                class="flex gap-1 p-1 items-center hover:bg-gray-200 cursor-pointer"
                                            >
                                                <delete-icon class="h-auto sm:w-5 w-4 p-0.5 text-delete" />
                                                <span>Delete</span>
                                            </div>
                                        </div>
                                    </context-menu>
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
            <component :is="importModal" v-if="importModal" :processing="dt.processing" :errors="errorBag" @uploadForm="dt.importCSV($event)" @close="closeDialog" :forceClose="dt.closeAllModal"/>
        </dialog-form-modal>
        <dialog-form-modal :show="showAddDialog && canCreate" @close="closeDialog">
            <component :is="addForm" v-if="addForm" :processing="dt.processing" :errors="errorBag" @submitForm="dt.create($event)" @close="closeDialog" :forceClose="dt.closeAllModal"/>
        </dialog-form-modal>
        <dialog-form-modal :show="showEditDialog && canUpdate" @close="closeDialog">
            <component :is="editForm" v-if="editForm" :processing="dt.processing" :errors="errorBag" @submitForm="dt.update($event)" @close="closeDialog" :forceClose="dt.closeAllModal" :data="toEditData"/>
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
import {Link} from '@inertiajs/vue3';
import {
    CancelButton,
    ContextMenu,
    CrcmTable,
    CrcmTbody,
    CrcmThead,
    DangerButton,
    NotFoundRow,
    PaginateBtn,
    PerPage,
    ProcessingRow,
    SearchBy,
    TbodyRow,
    TD,
    TH,
    TheadRow,
    TopActionBtn
} from '@/Components/CRCMDatatable/Components';

import ActionContainer from "@/Components/CRCMDatatable/Layouts/ActionContainer.vue";
import TopContainer from "@/Components/CRCMDatatable/Layouts/TopContainer.vue";
import LoaderIcon from "@/Components/Icons/LoaderIcon.vue";
import SearchFilter from "@/Components/CRCMDatatable/Components/SearchBox.vue";
import ArrowLeft from "@/Components/Icons/ArrowLeft.vue";
import ArrowRight from "@/Components/Icons/ArrowRight.vue";
import SelectedCount from "@/Components/CRCMDatatable/Components/SelectedCount.vue";
import selectedCount from "@/Components/CRCMDatatable/Components/SelectedCount.vue";
import DeleteIcon from "@/Components/Icons/DeleteIcon.vue";
import AddIcon from "@/Components/Icons/AddIcon.vue";
import RefreshIcon from "@/Components/Icons/RefreshIcon.vue";
import EditIcon from "@/Components/Icons/EditIcon.vue";
import ExportIcon from "@/Components/Icons/ExportIcon.vue";
import ImportIcon from "@/Components/Icons/ImportIcon.vue";
import CheckallIcon from "@/Components/Icons/CheckallIcon.vue";
import DeselectIcon from "@/Components/Icons/DeselectIcon.vue";
import DialogModal from "@/Components/DialogModal.vue";
import ToggleOffIcon from "@/Components/Icons/ToggleOffIcon.vue";
import ToggleOnIcon from "@/Components/Icons/ToggleOnIcon.vue";
import DialogFormModal from "@/Components/CRCMDatatable/Layouts/DialogFormModal.vue";
import ViewIcon from "@/Components/Icons/ViewIcon.vue";
</script>

<script>
import CRCMDatatable from "@/Components/CRCMDatatable/core/infra/CRCMDatatable.js";
import { router } from "@inertiajs/vue3";
import {defineAsyncComponent} from "vue";
import ApiService from "@/Modules/core/infrastructure/ApiService.ts";
import BaseClass from "@/Modules/core/domain/base/BaseClass";
export default {
    name: "CRCMDatatable",
    props: {
        baseUrl: {
            type: String,
            required: false,
            default: null,
        },
        baseModel: {
            type: [BaseClass, Function],
            required: false,
        },
        params: {
          type: Object,
          required: false,
          default: () => ({})
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
            inputWidth: 1,
            clickSortCtr: 0,
            rowContextMenu: null,
        }
    },
    computed: {
        data() {
            if (this.checkIfDataIsLoaded)
                return this.dt.response['data'];
            return [];
        },
        errorBag() {
            if (this.dt.errorBag)
                return this.dt.errorBag.errors;
            return null;
        },
        visibleColumns() {
            return this.dt.model.getColumns().filter(column => column.visible);
        },
        selected(){
            return this.dt.selected;
        },
        current_page() {
            if (this.checkIfDataIsLoaded)
                return this.dt.response['meta']['current_page'];
            return 1;
        },
        last_page() {
            if (this.checkIfDataIsLoaded)
                return this.dt.response['meta']['last_page'];
            return 1;
        },
        next_page() {
            if (this.checkIfDataIsLoaded)
                return this.dt.response['meta']['current_page'] + 1;
            return 1;
        },
        prev_page() {
            if (this.checkIfDataIsLoaded)
                return this.dt.response['meta']['current_page'] - 1;
            return 0;
        },
        first_page() {
            if (this.checkIfDataIsLoaded)
                return 1;
            return 1;
        },
        total_pages() {
            if (this.checkIfDataIsLoaded)
                return this.dt.response['meta']['last_page'];
            return 1;
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
        },
    },
    methods: {
        dataValue(label) {
            try {
                return Array.from(this.dt.model.getColumns()).find(col => col.key === label).visible && true
            } catch (e) {
                return false;
            }
        },
        showContextMenu(event, row) {
            event.preventDefault();
            this.rowContextMenu = row;
            this.$nextTick(() => {
                if (this.$refs.contextMenu && typeof this.$refs.contextMenu.showMenu === 'function') {
                    try {
                        this.$refs.contextMenu.showMenu(event);
                    } catch (e) {
                        console.log('Error at the showContextMenu');
                    }
                } else {
                    console.error('ContextMenu ref is not defined or showMenu is not a function');
                }
            });
        },
        getNestedValue(obj, path) {
            return path.split('.').reduce((acc, part) => acc && acc[part], obj);
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
        async showEditDialogFunc(id) {
            this.showModal = true;
            this.showEditDialog = true;
            this.toEditData = (await new ApiService(this.baseModel.showUri).show(id,{}, this.baseModel)).data;
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
            this.dt.errorBag = null;

            this.toDeleteId = null;
            this.toEditId = null;
        },
        async initializeDatatable() {
            this.dt = new CRCMDatatable(this.baseUrl, this.params, this.baseModel);
            //await this.dt.init();
            //the same as above, to initialize the table and the width of goto page field
            await this.$nextTick(() => {
                const input = this.$refs.input;
                if (input) {
                    this.inputWidth = input.scrollWidth;
                }
            });
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
        },
        onColumnSort(column) {
            if (!column.sortable) {
                return false;
            }

            this.clickSortCtr = (this.clickSortCtr + 1) % 3;

            if (this.clickSortCtr === 0) {
                return false;
            } else {
                return this.dt.sortFunc({ sort: column.key });
            }
        }

    },
    async mounted() {
        if (this.baseUrl){
            await this.initializeDatatable();
        }
    },
    setup() {
        return {
            CRCMDatatable,
        }
    }
};
</script>

