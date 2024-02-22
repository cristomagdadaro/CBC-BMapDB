<script>
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import {TWGPages} from "@/Pages/Projects/TWG/components/components.js";

export default {
    name: "ExpertsTable",
    computed: {
        TWGPages() {
            return TWGPages
        }
    },
    components: {CRCMDatatable}
}
</script>

<template>
    <h1 class="h1 text-center font-semibold uppercase">Experts</h1>
    <CRCMDatatable
        :base-url="TWGPages.api.expert.path"
        :base-model="TWGPages.api.expert.model"
        :add-form="TWGPages.api.expert.create.component"
        :edit-form="TWGPages.api.expert.edit.component"
    />
</template>

<style scoped>

</style>
