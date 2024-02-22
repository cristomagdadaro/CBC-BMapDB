<script>
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import { InventorySysPages } from "@/Pages/Services/InventorySys/components/components.js";
export default {
    name: "SupplierTable",
    computed: {
        InventorySysPages() {
            return InventorySysPages
        }
    },
    components: { CRCMDatatable },
}
</script>

<template>
    <h1 class="h1 text-center font-semibold uppercase">List of Suppliers</h1>
    <CRCMDatatable
        :base-url="InventorySysPages.api.supplier.path"
        :base-model="InventorySysPages.api.supplier.model"
        :add-form="null"
        :edit-form="null"
    />
</template>

<style scoped>

</style>
