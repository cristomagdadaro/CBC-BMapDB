<script>
import DeleteIcon from '@/Components/Icons/DeleteIcon.vue';
import CloseIcon from '@/Components/Icons/CloseIcon.vue';
import DownloadIcon from '@/Components/Icons/DownloadIcon.vue';
import RefreshIcon from '@/Components/Icons/RefreshIcon.vue';
import AddIcon from '@/Components/Icons/AddIcon.vue';
import CollapseIcon from "@/Components/Icons/CollapseIcon.vue";
import ExpandIcon from "@/Components/Icons/ExpandIcon.vue";
import CheckallIcon from "@/Components/Icons/CheckallIcon.vue";
import UploadIcon from "@/Components/Icons/UploadIcon.vue";
import DtLength from "@/Components/DataTable/DtComponents/DtLength.vue";
import DtSearch from "@/Components/DataTable/DtComponents/DtSearch.vue";
import DtSearchBy from "@/Components/DataTable/DtComponents/DtSearchBy.vue";
import DtPaginateBtn from "@/Components/DataTable/DtComponents/DtPaginateBtn.vue";
import DtPaginateDetail from "@/Components/DataTable/DtComponents/DtPaginateDetail.vue";
import DtPageBtn from "@/Components/DataTable/DtComponents/DtPageBtn.vue";
import DtActionBtn from "@/Components/DataTable/DtComponents/DtActionBtn.vue";
import DtTHead from "@/Components/DataTable/DtComponents/DtTHead.vue";
import DtTh from "@/Components/DataTable/DtComponents/DtTh.vue";
import DtBody from "@/Components/DataTable/DtComponents/DtBody.vue";
import DtFooter from "@/Components/DataTable/DtComponents/DtFooter.vue";
import DtTable from "@/Components/DataTable/DtComponents/DtTable.vue";
import DtPaginateContainer from "@/Components/DataTable/DtComponents/DtPaginateContainer.vue";
import DtTopContainer from "@/Components/DataTable/DtComponents/DtTopContainer.vue";
import DtContainer from "@/Components/DataTable/DtComponents/DtContainer.vue";
import DtActionContainer from "@/Components/DataTable/DtComponents/DtActionContainer.vue";
import DtLengthContainer from "@/Components/DataTable/DtComponents/DtLengthContainer.vue";
import SpinnerIcon from "@/Components/Icons/SpinnerIcon.vue";
import DialogModal from "@/Components/DialogModal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import TextField from "@/Components/Form/TextField.vue";
import TWGEdit from "@/Pages/Projects/TWG/presentation/components/expert/CreateExpertForm.vue";
import {markRaw} from 'vue';
import axios from 'axios';
import {Link} from "@inertiajs/vue3";
import EditIcon from "@/Components/Icons/EditIcon.vue";
import ViewIcon from "@/Components/Icons/ViewIcon.vue";
import Notification from "@/Components/Modal/Notification/Notification.js";

export default {
    components: {
        DeleteIcon,
        CloseIcon,
        DownloadIcon,
        RefreshIcon,
        AddIcon,
        CollapseIcon,
        ExpandIcon,
        CheckallIcon,
        UploadIcon,
        DtLength,
        DtSearch,
        DtSearchBy,
        DtPaginateBtn,
        DtPaginateDetail,
        DtPageBtn,
        DtActionBtn,
        DtTHead,
        DtTh,
        DtBody,
        DtFooter,
        DtTable,
        DtPaginateContainer,
        DtTopContainer,
        DtContainer,
        DtActionContainer,
        DtLengthContainer,
        SpinnerIcon,
        DialogModal,
        SecondaryButton,
        DangerButton,
        TextField,
        TWGEdit,
        Link,
        EditIcon,
        ViewIcon,
    },
    props: {
        columnsLarge: {
            type: Array,
            required: true,
        },
        apiLink: {
            type: [Array, Object],
            required: true,
        },
    },
    data: () => ({
        deleteId: null,
        showDestroyModal: false,
        showDestroyModalMsg: '',
        showCreateModal: false,
        showEditModal: false,
        toEditId: null,
        dtMessage: '',
        showMenu: false,
        columns: [],    // columns to be displayed
        processing: false, // show spinner
        viewSize: false, // default is large view
        pageStart: 0,
        totalCount: 0,
        pageNumber: 1,
        perPage: 20,
        sortedColumn: 'id', // default column to be sorted
        sortDir: 'desc', // default sort direction
        totalPages: 0,
        totalRecords: 0,
        search: null,
        searchBy: '*',
        selected: [],
        currentPageSelected: [],
        completedCount: 0,
        data: [],
        DtLengthOptions: [20, 50, 100, 200],
        contextMenu: [
            {
                title: 'Delete',
                func: null,
                icon: markRaw(DeleteIcon),
            },
            {
                title: 'Edit',
                func: null,
                icon: markRaw(EditIcon),
            }
        ],
        actionButton: {
            data: null,
            icon: [markRaw(ViewIcon), markRaw(DeleteIcon), markRaw(EditIcon)],
            name: 'actions',
            title: 'Actions',
            searchable: false,
            orderable: false,
            collapsable: false,
            className: 'dt-center flex justify-center',
        },
    }),
    mounted() {
        //Initialize the data request
        this.autoGetData();
        this.changeSizeView();
    },
    methods: {
        getDataTableData: function (){
            this.getData();
        },
        createModal() {
            this.showCreateModal = true;
        },
        editModal(id) {
            this.toEditId = id;
            this.showEditModal = true;
        },
        getEditData(id, data) {
            axios.get(route(this.apiLink.edit, id))
                .then(response => {
                    data = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        deleteRecord(id, multi = false) {
            if (multi) {
                this.deleteMultiRecord();
            } else {
                this.deleteSingleRecord(id);
            }
        },
        showDeleteModal(id) {
            if (typeof id !== 'object') {
                this.showDestroyModalMsg = 'Are you sure you want to delete this record?';
                this.deleteId = id;
                this.selected = [];
            } else {
                this.showDestroyModalMsg = `Are you sure you want to delete these ${id.length} records?`;
                this.selected = id;
                this.deleteId = null;
            }
            this.showDestroyModal = true;
        },
        deleteSingleRecord(id) {
            this.processing = true;
            this.dtMessage = 'Please wait while deleting records...';
            axios.delete(route(this.apiLink.destroy, id))
                .then(response => {
                    Notification.pushNotification(response.data.notification);
                    this.getData();
                })
                .catch(error => {
                    console.log(error);
                })
                .finally(() => {
                    this.processing = false;
                    this.deleteId = null;
                    this.showDestroyModal = false;
                });
        },
        async deleteMultiRecord() {
            this.processing = true;
            this.dtMessage = `Please wait while deleting records...`;
            await axios.delete(route(this.apiLink.destroy, {id: this.selected}))
                .then(response => {
                    Notification.pushNotification(response.data.notification);
                    this.getData();
                })
                .catch(error => {
                    console.log(error);
                })
                .finally(() => {
                    this.processing = false;
                    this.showDestroyModal = false;
                    this.selected = [];
                });
        },
        selectRecord(event, id) {
            // Check if the ctrl key is held down
            const ctrlKey = event.metaKey || event.ctrlKey;

            if (this.selected.includes(id)) {
                // Remove the record from the selection
                this.selected = this.selected.filter(i => i !== id);
            } else {
                // Add the record to the selection
                if (ctrlKey) {
                    // Multi-select mode, add to selection
                    this.selected.push(id);
                } else {
                    // Single-select mode, replace selection with this record
                    this.selected = [id];
                }
            }
        },
        selectAllShown() {
            if (this.selected.length < this.totalRecords) {
                this.data.forEach(record => {
                    if (!this.selected.includes(record.id)) {
                        this.selected.push(record.id);
                        this.currentPageSelected.push(record.id);
                    }
                });
            } else {
                this.selected = [];
                this.currentPageSelected = [];
            }
        },
        async selectAll() {
            if (this.selected.length < this.totalRecords) {
                await axios.get(route(this.apiLink.index))
                    .then(response => {
                        this.selected = response.data.data.map(record => record.id);
                        //console.log(response.data.data);
                    })
                    .catch(error => {
                        console.log(error);
                    });
            } else {
                this.selected = [];
                //this.currentPageSelected = [];
            }
        },
        deselectAll() {
            this.selected = [];
            this.currentPageSelected = [];
        },
        async getData() {
            this.processing = true;
            this.setDtMesssage('Please wait while retrieving data...');
            await axios.get(route(this.apiLink.table), {
                params: {
                    page: this.pageNumber,
                    per_page: this.perPage,
                    search: this.search,
                    search_by: this.searchBy ? this.searchBy : '*',
                    sort: this.sortedColumn,
                    sort_dir: this.sortDir,
                }
            })
                .then(response => {
                    this.data = response.data.data;
                    this.totalCount = response.data.totalCount;
                    this.totalPages = response.data.totalPages;
                    this.perPage = response.data.perPage;
                    this.totalRecords = response.data.totalRecords;
                    this.pageStart = this.pageNumber * this.perPage - this.perPage + 1;
                    this.processing = false;
                })
                .catch(error => {
                    console.log(error);
                })
                .finally(() => {
                    this.processing = false;
                });
        },
        nextPage() {
            if (this.pageNumber < this.totalPages) {
                this.pageNumber++;
                this.getData();
            }
        },
        previousPage() {
            if (this.pageNumber > 1) {
                this.pageNumber--;
                this.getData();
            }
        },
        firstPage() {
            if (this.pageNumber === 1)
                return;
            this.pageNumber = 1;
            this.getData();
        },
        lastPage() {
            if (this.pageNumber === this.totalPages)
                return;
            this.pageNumber = this.totalPages;
            this.getData();
        },
        changePageNumber(page) {
            this.pageNumber = page;
            this.getData();
        },
        autoGetData() {
            this.getData();
            //Auto get data every 5 minutes
            setInterval(() => {
                this.getData();
            }, 3000000);
        },
        sortColumn(col) {
            if (!col.orderable)
                return;
            if (col.name === this.sortedColumn) {
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortedColumn = col.name;
                this.sortDir = 'asc';
            }
            this.getData();
        },
        refreshData() {
            this.getData();
        },
        changeSizeView() {
            this.viewSize = !this.viewSize;
            if (this.viewSize) {
                this.columns = this.columnsLarge;
            } else {
                this.columns = this.columnsSmall;
            }
            // only append the action column if it is not in the columns array
            /*if (!this.columns.includes(this.actionButton)) {
                this.columns.push(this.actionButton);
            }*/
        },
        exportToCsv() {
            this.processing = true;
            axios.get(route(this.apiLink.index))
                .then((response) => {
                    const currentDate = new Date();
                    const formattedDate = currentDate.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit'
                    });
                    const filename = `${this.apiLink.index}-${formattedDate}.csv`;

                    const rows = response.data.data;
                    // Convert the array of objects to a CSV string
                    const rowsArray = Array.from(rows);

                    // append infront the header row, get the column headers from the database
                    rowsArray.unshift(Object.keys(rows[0]));
                    const csvData = rowsArray.map(row => Object.values(row).map(val => {
                        if (val === null) {
                            return '""';
                        }
                        // convert val to string and check if it contains a comma and trim
                        val = val.toString().trim();
                        val = val.replace(/(\r\n|\n|\r)/gm, "");
                        if (val.includes(',')) {
                            // escape the comma
                            val = `"${val}"`;
                        }
                        return val;
                    }).join(",")).join("\n");

                    const blob = new Blob([csvData], {type: 'text/csv;charset=utf-8;'});
                    if (navigator.msSaveBlob) {
                        navigator.msSaveBlob(blob, filename);
                    } else {
                        const link = document.createElement('a');
                        if (link.download !== undefined) {
                            const url = URL.createObjectURL(blob);
                            link.setAttribute('href', url);
                            link.setAttribute('download', filename);
                            link.style.visibility = 'hidden';
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        }
                    }
                })
                .catch((error) => {
                    console.log(error);
                })
                .finally(() => {
                    this.processing = false;
                });
        },
        importFromCsv() {
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.accept = '.csv';
            fileInput.onchange = () => {
                const file = fileInput.files[0];
                const reader = new FileReader();
                reader.readAsText(file);
                reader.onload = async () => {
                    this.processing = true;
                    this.dtMessage = 'Please wait while importing data...';
                    const csvData = reader.result;
                    const rowsArray = csvData.split('\n');
                    const headers = rowsArray[0].split(',');
                    const rows = [];
                    for (let i = 1; i < rowsArray.length; i++) {
                        // split the row into values and can handle commas inside double quotes
                        const values = rowsArray[i].split(/,(?=(?:(?:[^"]*"){2})*[^"]*$)/);
                        const row = {};
                        for (let j = 0; j < headers.length; j++) {
                            // if the value is empty, set it to null
                            if (values[j] === "\"\"" || values[j] === undefined || values[j] === null) {
                                values[j] = null;
                            }
                            row[headers[j]] = values[j];
                        }
                        rows.push(row);
                    }
                    // Send the imported data to the server
                    await axios.post(route(this.apiLink.import), rows)
                        .then((response) => {
                            Notification.pushNotification(response.data.notification);
                            // Refresh the data table
                            this.getData();
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                };
            };
            fileInput.click();
        },
        setDtMesssage(message) {
            this.dtMessage = message;
        },
        isColumnSorted(col) {
            return col.name === this.sortedColumn;
        },
        closeModal() {
            this.showDestroyModal = false;
            this.showCreateModal = false;
            this.showEditModal = false;
        },
    },
    computed: {
        isAllSelected() {
            return this.selected.length < this.totalRecords;
        },
    },
}
</script>
<template>
    <DtContainer>
        <!--        <DtProcessing v-if="processing" >{{ completedCount? completedCount:'' }}</DtProcessing>-->
        <DialogModal :show="showDestroyModal" @close="closeModal">
            <template #title>
                Delete Record
            </template>
            <template #content>
                {{ showDestroyModalMsg }}
            </template>
            <template #footer>
                <SecondaryButton @click="closeModal">
                    Cancel
                </SecondaryButton>
                <DangerButton
                    :class="{ 'opacity-25': processing }"
                    :disabled="processing"
                    class="ml-3"
                    @click="deleteRecord(deleteId, selected.length > 1)"
                >
                    Delete
                </DangerButton>
            </template>
        </DialogModal>
        <DialogModal :show="showCreateModal" @close="closeModal">
            <template #title>
                Register a Project
            </template>
            <template #content>
                <component :is="apiLink.create" :action="apiLink.store"/>
            </template>
        </DialogModal>
        <DialogModal :show="showEditModal" @close="closeModal">
            <template #title>
                Update this Project
            </template>
            <template #content>
                <component :is="apiLink.edit" :action="apiLink.update" :dataLink="route(apiLink.getEditData,toEditId)" />
            </template>
        </DialogModal>
        <DtTopContainer>
            <DtActionContainer>
                <DtActionBtn v-if="apiLink.create && typeof(apiLink) == 'object'" class="bg-yellow-500"
                             @click="createModal">
                    <AddIcon class="w-4 mr-1"/>
                    New
                </DtActionBtn>
                <DtActionBtn v-else :href="route(apiLink.create)" class="bg-yellow-500">
                    <AddIcon class="w-4 mr-1"/>
                    New
                </DtActionBtn>
                <DtActionBtn class="bg-blue-500" @click="refreshData">
                    <RefreshIcon class="w-4 mr-1"/>
                    Refresh
                </DtActionBtn>
                <DtActionBtn v-if="totalRecords" class="bg-green-600" @click="exportToCsv">
                    <DownloadIcon class="w-4 mr-1"/>
                    Export
                </DtActionBtn>
                <DtActionBtn v-if="false" class="bg-teal-600" @click="importFromCsv">
                    <UploadIcon class="w-4 mr-1"/>
                    Import
                </DtActionBtn>
                <DtActionBtn v-if="selected.length > 1 && apiLink.destroy && $page.props.auth.user.role === '1'"
                             class="bg-red-600" @click="showDeleteModal(selected)">
                    <DeleteIcon class="w-4 mr-1"/>
                    Delete
                </DtActionBtn>
                <DtActionBtn v-if="false" class="bg-vsu-yellow-green" @click="changeSizeView">
                    <template v-if="viewSize">
                        <CollapseIcon class="w-3 mr-1"/>
                        Collapse
                    </template>
                    <template v-else>
                        <ExpandIcon class="w-3 mr-1"/>
                        Expand
                    </template>
                </DtActionBtn>
                <DtActionBtn v-if="isAllSelected && apiLink.destroy && $page.props.auth.user.role === '1'"
                             class="bg-orange-500" @click="selectAllShown">
                    <CheckallIcon class="w-4 mr-1"/>
                    Select All Shown
                </DtActionBtn>
                <DtActionBtn v-if="isAllSelected && $page.props.auth.user.role === '1' && false" class="bg-indigo-500"
                             @click="selectAll">
                    <CheckallIcon class="w-4 mr-1"/>
                    Select All
                </DtActionBtn>
                <DtActionBtn v-if="selected.length && apiLink.destroy && $page.props.auth.user.role === '1'"
                             class="bg-cbc-dark-green" @click="deselectAll">
                    <CloseIcon class="w-4 mr-1"/>
                    Deselect All
                </DtActionBtn>
            </DtActionContainer>
            <DtLengthContainer>
                <DtLength v-model="perPage" :options="DtLengthOptions" @change="refreshData"/>
                <div class="flex items-center rounded-sm shadow-sm border">
                    <DtSearchBy v-model="searchBy" :columns="columnsLarge" @change="refreshData()"/>
                    <DtSearch v-model="search" :columns="columns" :func="refreshData" :searchBy="searchBy"/>
                </div>
            </DtLengthContainer>
        </DtTopContainer>
        <DtTable ref="table" classes="max-h-screen overflow-y-auto">
            <DtTHead class="bg-gray-300">
                <!-- <DtTh title="" class="max-w-fit absolute" sortDir="asc" :isSortedColumn="false" /> -->
                <DtTh v-for="col in columns" :key="col.data" :isSortedColumn="isColumnSorted(col)"
                      :sortDir="sortDir" :title="col.title" class="text-gray-700 border-x border-gray-300"
                      @click="sortColumn(col)"/>
                <DtTh v-if="apiLink.destroy && $page.props.auth.user.role === '1'" class="text-gray-700 border-x border-gray-300" sort-dir="asc" title="Action" :isSortedColumn="false"/>
            </DtTHead>
            <DtBody>
                <td v-if="processing" :colspan="columns.length" class="text-center">
                    <div class="flex justify-center">
                        <SpinnerIcon class="w-5 mr-1 animate-spin"/>
                        {{ dtMessage }}
                    </div>
                </td>
                <tr v-else-if="!data.length">
                    <td :colspan="columns.length" class="text-center">No data available</td>
                </tr>
                <tr v-for="item in data" v-else
                    :class="{ 'bg-gray-300 text-gray-900 border-x-0': selected.includes(item.id) }"
                    class="hover:bg-gray-200 border border-x-0"
                    @click="selectRecord($event, item.id)">
                    <!--index -->
                    <!-- <td class="whitespace-nowrap max-w-fit">
                        {{ pageStart + data.indexOf(item) }}
                    </td> -->
                    <template v-for="col in columns" :key="col.data">
                        <!-- for data -->
                        <td v-if="col.data" :class="col.className" class="border-gray-200 border max-w-xs">
                            {{ item[col.data] }}
                        </td>
                    </template>
                    <!-- for actions -->
                    <td class="whitespace-nowrap">
                        <div v-if="selected.includes(item.id) && selected.length <= 1"
                             class="flex justify-evenly container">
                            <Link v-if="apiLink.show" :href="route(apiLink.show, item.id)" class="w-5 flex hover:text-green-900 hover:scale-110 translate-x-0 text-green-600 duration-100 ease-in"
                                  title="View">
                                <ViewIcon class="w-5"/>
                            </Link>
                            <button v-if="apiLink.edit && typeof(apiLink) == 'object'" class="w-5 flex hover:text-yellow-600 hover:scale-110 translate-x-0 text-yellow-500 duration-100 ease-in"
                                    title="Update"
                                    @click="editModal(item.id)">
                                <EditIcon class="w-5"/>
                            </button>
                            <Link v-else-if="apiLink.edit" :href="route(apiLink.edit, item.id)" class="w-5 flex hover:text-yellow-600 hover:scale-110 translate-x-0 text-yellow-500 duration-100 ease-in"
                                  title="Update">
                                <EditIcon class="w-5"/>
                            </Link>
                            <button v-if="apiLink.destroy && $page.props.auth.user.role === '1'" class="w-5 flex hover:text-red-600 hover:scale-110 translate-x-0 text-gray-500 duration-100 ease-in"
                                    title="Delete"
                                    @click="showDeleteModal(item.id)">
                                <DeleteIcon class="w-5"/>
                            </button>
                        </div>
                    </td>
                </tr>
            </DtBody>
        </DtTable>
        <DtFooter>
            <DtPaginateDetail>Showing {{ pageStart }} to {{ pageStart + data.length - 1 }} of {{ totalRecords }}
                entries
            </DtPaginateDetail>
            <DtPageBtn :changePageNumber="changePageNumber" :pageNumber="pageNumber" :selected="selected"
                       :totalPages="totalPages"/>
            <DtPaginateContainer>
                <DtPaginateBtn :disabled="pageNumber === 1" :func="firstPage">First</DtPaginateBtn>
                <DtPaginateBtn :disabled="pageNumber === 1" :func="previousPage">Previous</DtPaginateBtn>
                <DtPaginateBtn :disabled="pageNumber === totalPages" :func="nextPage">Next</DtPaginateBtn>
                <DtPaginateBtn :disabled="pageNumber === totalPages" :func="lastPage">Last</DtPaginateBtn>
            </DtPaginateContainer>
        </DtFooter>
    </DtContainer>
</template>
<style>
.asc::after {
    content: '▲';
}

.desc::after {
    content: '▼';
}
</style>
