<script>
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import {TWGPages} from "@/Pages/Projects/TWG/components/components.js";

export default {
    name: "ProjectsTable",
    computed: {
        TWGPages() {
            return TWGPages
        }
    },
    components: {CRCMDatatable}
}
</script>

<template>
    <h1 class="h1 text-center font-semibold uppercase">Projects</h1>
    <CRCMDatatable
        :base-url="TWGPages.api.project.path"
        :base-model="TWGPages.api.project.model"
    />
</template>

<style scoped>

</style>
