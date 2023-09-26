<script setup>
import PageLayout from "@/Layouts/PageLayout.vue";
import {Head} from "@inertiajs/vue3";

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
        className: 'dt-center dt-border-sm px-2 text-center'
    },
]

const api =  {
    // create form
    create: 'laboratory.fecalysis.create',
    // edit form
    edit: 'laboratory.fecalysis.edit',
    //view form
    show: 'laboratory.fecalysis.show',
    // return all data
    index: 'twg',
    // return data compatible for datatable requests
    table: 'twgtable',
    // store data from create form
    store: 'api.fecalysis.store',
    // update data from edit form
    update: 'api.fecalysis.update',
    // delete a specific data
    destroy: 'api.fecalysis.destroy',
    // import data from excel
    import: 'api.fecalysis.import',
}
</script>

<template>
    <Head title="TWG Database" />
    <PageLayout>

    </PageLayout>
</template>
