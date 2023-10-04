<template>
    <Head title="TWG Database" />
    <AppLayout :is-wide-display="isWideDisplay">
        <div class="p-5">
            <Tab :tabs="tabs">
                <template #tab1>
                    <data-table v-if="$page.props.auth.user" :api-link="api" :columns-large="cols" :columns-small="cols" />
                    <p v-else>Please login to view the data</p>
                </template>
                <template #tab2>
                    Tab 2
                </template>
                <template #tab3>
                    Tab 3
                </template>
            </Tab>

        </div>
    </AppLayout>
</template>
<script setup>
import DataTable from "@/Components/DataTable/DataTable.vue";
import {Head} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import TWGCreateProject from "@/Pages/Projects/TWG/TWGCreateProject.vue";
import {markRaw} from "vue";
import Tab from "@/Components/Tab/Tab.vue";
const isWideDisplay = true;

const tabs = [
    {
        name: "tab1",
        label: "Projects",
        active: true,
    },
    {
        name: "tab2",
        label: "Products",
        active: false,
    },
    {
        name: "tab3",
        label: "Services",
        active: false,
    },
]
const cols = [
    {
        data: 'id',
        name:'id',
        title: 'ID',
        searchable: true,
        orderable: true,
        collapsable: false,
        className: 'px-2 text-center'
    },
    {
        data: 'title',
        name:'title',
        title: 'Title',
        searchable: true,
        orderable: true,
        collapsable: false,
        className: ' px-2'
    },{
        data: 'objective',
        name:'objective',
        title: 'Objective',
        searchable: true,
        orderable: true,
        collapsable: false,
        className: 'px-2'
    },{
        data: 'expected_output',
        name:'expected_output',
        title: 'Expected Output',
        searchable: true,
        orderable: true,
        collapsable: false,
        className: 'px-2'
    },{
        data: 'project_leader',
        name:'project_leader',
        title: 'Project Leader',
        searchable: true,
        orderable: true,
        collapsable: false,
        className: 'px-2 text-center'
    },{
        data: 'funding_agency',
        name:'funding_agency',
        title: 'Funding Agency',
        searchable: true,
        orderable: true,
        collapsable: false,
        className: 'px-2'
    },{
        data: 'duration',
        name:'duration',
        title: 'Duration',
        searchable: true,
        orderable: true,
        collapsable: false,
        className: 'px-2'
    },{
        data: 'status',
        name:'status',
        title: 'Status',
        searchable: true,
        orderable: true,
        collapsable: false,
        className: 'px-2'
    }
]

const api =  {
    // create form
    create: markRaw(TWGCreateProject),
    // edit form
    edit: markRaw(TWGCreateProject),
    getEditData: 'twg.edit.project.data',
    //view form
    //show: 'laboratory.fecalysis.show',
    // return all data
    index: 'twg.export.project',
    // return data compatible for datatable requests
    table: 'twg.table.projects',
    // store data from create form
    //store: 'api.fecalysis.store',
    // update data from edit form
    //update: 'api.fecalysis.update',
    // delete a specific data
    destroy: 'twg.destroy.project',
    // import data from excel
    //import: 'api.fecalysis.import',
}

</script>
