<template>
    <div id="dtContainer" class="bg-gray-200">
        <top-container>
            <action-container>
                <top-action-btn>Add</top-action-btn>
                <top-action-btn>Edit</top-action-btn>
                <top-action-btn>Delete</top-action-btn>
                <top-action-btn>Refresh</top-action-btn>
                <top-action-btn>Export</top-action-btn>
                <top-action-btn>Import</top-action-btn>
                <top-action-btn>Select All</top-action-btn>
                <top-action-btn>Deselect</top-action-btn>
            </action-container>
        </top-container>
        <filter-container>
            <div class="flex items-center gap-1">
                <span>Show</span>
                <select class="border-0 py-0.5 rounded text-center" @change="changePerPage($event)">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>entries</span>
            </div>
            <input class="border-0 py-1.5 rounded" type="text" id="dtSearch" placeholder="Search" @keyup.capture.enter="dt.searchFunc($event.target.value)" />
        </filter-container>
        <div id="dtTableContainer" class="flex w-full justify-center">
            <table id="dtTable" class="w-full">
                <thead id="dtHeader">
                <tr class="dtHeaderRow border-y border-gray-700">
                    <th class="dtHeaderColumn border-x border-gray-700" v-for="column in columns" :key="column" @click="changeSort(column.name)">
                        <div class="dtHeaderCell">
                    <span class="dtHeaderCellText">
                        {{ column.label }}
                    </span>
                            <span class="dtHeaderCellSortIcon asc"></span>
                        </div>
                    </th>
                </tr>
                </thead>
                <tbody id="dtBody">
                <tr id="dtRowProcessing" v-if="false">
                    Processing
                </tr>
                <tr class="dtBodyRow" v-for="row in response['data']">
                    <td class="dtBodyCell" v-for="cell in row" :key="cell">
                        {{ cell }}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="dtFooterContainer" class="flex justify-between gap-5" v-if="response instanceof BaseResponse">
            <div id="dtPageDetails">
                Showing {{ response['meta']['from'] }} to {{ response['meta']['to'] }} of {{ response['meta']['total'] }} entries
            </div>
            <div id="dtPaginatorContainer" class="flex gap-0.5 items-center">
                <paginate-btn @click="paginateFunc(1)">First</paginate-btn>
                <paginate-btn :disabled="true" @click="paginateFunc(response['meta']['from'])">Prev</paginate-btn>
                <span class="text-sm mx-1">Page: <span>{{ response['meta']['current_page'] }}</span></span>
                <paginate-btn @click="paginateFunc(response['meta']['next'])">Next</paginate-btn>
                <paginate-btn @click="paginateFunc(response['meta']['last_page'])">Last</paginate-btn>
            </div>
        </div>
    </div>
    {{response}}
</template>

<script setup>
import { onMounted, ref } from "vue";
import BaseResponse from "@/Modules/core/infrastructure/BaseResponse.js";
import BaseRequest from "@/Modules/core/infrastructure/BaseRequest.js";
import CRCMDatatable from "@/Modules/core/components/CRCMDatatable.js";
import TopActionBtn from "@/Components/CRCMDatatable/Components/TopActionBtn.vue";
import PaginateBtn from "@/Components/CRCMDatatable/Components/PaginateBtn.vue";
import ActionContainer from "@/Components/CRCMDatatable/Layouts/ActionContainer.vue";
import TopContainer from "@/Components/CRCMDatatable/Layouts/TopContainer.vue";
import FilterContainer from "@/Components/CRCMDatatable/Layouts/FilterContainer.vue";

const columns = ref('');
const link = route('account.for.accounts', 1);
const response = ref(BaseResponse);
const dt = new CRCMDatatable(columns, link)

onMounted(async () => {
    await dt.init();
    response.value = dt.response;
    columns.value = dt.columns;
});
</script>


<style>
.asc::after {
    content: '▲';
}

.desc::after {
    content: '▼';
}
</style>


