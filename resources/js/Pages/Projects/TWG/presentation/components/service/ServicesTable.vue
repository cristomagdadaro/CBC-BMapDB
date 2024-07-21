<script>
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import {TWGPages} from "@/Pages/Projects/TWG/components/components.js";

export default {
    name: "ServicesTable",
    computed: {
        TWGPages() {
            return TWGPages
        }
    },
    components: {CRCMDatatable}
}
</script>

<template>
    <h1 class="h1 text-center font-semibold uppercase">Services/Protocols</h1>
    <CRCMDatatable
        :base-url="TWGPages.api.service.path"
        :base-model="TWGPages.api.service.model"
        :add-form="TWGPages.api.service.create.component"
        :edit-form="TWGPages.api.service.edit.component"
    />
</template>

<style scoped>

</style>
