<script setup>
import PageLayout from "@/Layouts/PageLayout.vue";
import {Head} from "@inertiajs/vue3";
import DataTable from "@/Components/DataTable/DataTable.vue";
const cols = [
    {
        data: 'id',
        name:'id',
        title: 'ID',
        searchable: true,
        orderable: true,
        collapsable: false,
        className: 'dt-center dt-border-sm px-2 text-center'
    },
    {
        data: 'fname',
        name:'fname',
        title: 'First Name',
        searchable: true,
        orderable: true,
        collapsable: false,
        className: 'dt-center dt-border-sm px-2'
    },{
        data: 'mname',
        name:'mname',
        title: 'Middle Name',
        searchable: true,
        orderable: true,
        collapsable: false,
        className: 'dt-center dt-border-sm px-2'
    },{
        data: 'lname',
        name:'lname',
        title: 'Last Name',
        searchable: true,
        orderable: true,
        collapsable: false,
        className: 'dt-center dt-border-sm px-2'
    },{
        data: 'suffix',
        name:'suffix',
        title: 'Suffix',
        searchable: true,
        orderable: true,
        collapsable: false,
        className: 'dt-center dt-border-sm px-2 text-center'
    },{
        data: 'position',
        name:'position',
        title: 'Position',
        searchable: true,
        orderable: true,
        collapsable: false,
        className: 'dt-center dt-border-sm px-2'
    },{
        data: 'educ_level',
        name:'educ_level',
        title: 'Degree',
        searchable: true,
        orderable: true,
        collapsable: false,
        className: 'dt-center dt-border-sm px-2'
    },{
        data: 'expertise',
        name:'expertise',
        title: 'Expertise',
        searchable: true,
        orderable: true,
        collapsable: false,
        className: 'dt-center dt-border-sm px-2'
    },{
        data: 'email',
        name:'email',
        title: 'Email',
        searchable: true,
        orderable: true,
        collapsable: false,
        className: 'dt-center dt-border-sm px-2'
    },{
        data: 'mobile_no',
        name:'mobile_no',
        title: 'Mobile',
        searchable: true,
        orderable: true,
        collapsable: false,
        className: 'dt-center dt-border-sm px-2'
    },

]

const api =  {
    // create form
    create: 'twgcreate',
    // edit form
    edit: 'twgedit',
    //view form
    //show: 'laboratory.fecalysis.show',
    // return all data
    index: 'twgexport',
    // return data compatible for datatable requests
    table: 'twgtable',
    // store data from create form
    //store: 'api.fecalysis.store',
    // update data from edit form
    //update: 'api.fecalysis.update',
    // delete a specific data
    destroy: 'twgdestroy',
    // import data from excel
    //import: 'api.fecalysis.import',
}

const isWideDisplay = true;
</script>

<template>
    <Head title="TWG Database" />
    <PageLayout :is-wide-display="isWideDisplay">
        <data-table v-if="$page.props.auth.user" :api-link="api" :columns-large="cols" :columns-small="cols" />
        <p v-else>Please login to view the data</p>
    </PageLayout>
</template>
