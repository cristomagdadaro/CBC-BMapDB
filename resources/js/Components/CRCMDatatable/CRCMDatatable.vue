<template>
    <div id="dtContainer" class="flex flex-col gap-1 bg-gray-200 px-2" v-if="dt instanceof CRCMDatatable">
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
            <per-page @changePerPage="dt.perPageFunc({ per_page: $event })" />
            <search-filter @searchString="dt.searchFunc({ search: $event })" />
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
                <processing-row :colspan="dt.columns.length" />
                <tbody-row
                    v-if="dt.response['data'].length"
                    v-for="row in dt.response['data']"
                    @click="dt.addSelected(row.id)"
                    :isSelected="dt.isSelected(row.id)"
                >
                        <!-- Cell Data -->
                        <t-d v-for="cell in row" :key="cell"> {{ cell }} </t-d>
                        <!-- Cell Actions -->
                        <t-d>Delete</t-d>
                </tbody-row>
                <not-found-row v-else :colspan="dt.columns.length" />
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
import BaseResponse from "@/Modules/core/infrastructure/BaseResponse.js";
import TopActionBtn from "@/Components/CRCMDatatable/Components/TopActionBtn.vue";
import PaginateBtn from "@/Components/CRCMDatatable/Components/PaginateBtn.vue";
import ActionContainer from "@/Components/CRCMDatatable/Layouts/ActionContainer.vue";
import TopContainer from "@/Components/CRCMDatatable/Layouts/TopContainer.vue";
import FilterContainer from "@/Components/CRCMDatatable/Layouts/FilterContainer.vue";
import LoaderIcon from "@/Components/Icons/LoaderIcon.vue";
import PerPage from "@/Components/CRCMDatatable/Components/PerPage.vue";
import SearchFilter from "@/Components/CRCMDatatable/Components/SearchFilter.vue";
import TH from "@/Components/CRCMDatatable/Components/TH.vue";
import TD from "@/Components/CRCMDatatable/Components/TD.vue";
import ProcessingRow from "@/Components/CRCMDatatable/Components/ProcessingRow.vue";
import TbodyRow from "@/Components/CRCMDatatable/Components/TbodyRow.vue";
import NotFoundRow from "@/Components/CRCMDatatable/Components/NotFoundRow.vue";
import ArrowLeft from "@/Components/Icons/ArrowLeft.vue";
import ArrowRight from "@/Components/Icons/ArrowRight.vue";
import CircleOneIcon from "@/Components/Icons/CircleOneIcon.vue";
</script>

<script>
import CRCMDatatable from "@/Modules/core/components/CRCMDatatable.js";
export default {
    name: "CRCMDatatable",
    components: {
        CRCMDatatable,
    },
    data() {
        return {
            dt: null,
            link: null,
        }
    },
    mounted() {
        this.link = route('account.for.accounts', 1);
        this.dt = new CRCMDatatable(this.link);
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


