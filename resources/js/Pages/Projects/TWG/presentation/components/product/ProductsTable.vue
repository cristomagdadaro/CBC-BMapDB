<script>
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import {TWGPages} from "@/Pages/Projects/TWG/components/components.js";

export default {
    name: "ProductsTable",
    computed: {
        TWGPages() {
            return TWGPages
        }
    },
    components: {CRCMDatatable}
}
</script>

<template>
    <h1 class="h1 text-center font-semibold uppercase">Products</h1>
    <CRCMDatatable
        :base-url="TWGPages.api.product.path"
        :base-model="TWGPages.api.product.model"
    />
</template>

<style scoped>

</style>
