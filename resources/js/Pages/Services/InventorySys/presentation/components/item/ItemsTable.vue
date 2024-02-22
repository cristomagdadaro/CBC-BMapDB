<script>
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import { InventorySysPages } from "@/Pages/Services/InventorySys/components/components.js";
export default {
    name: "ItemsTable",
    computed: {
        InventorySysPages() {
            return InventorySysPages
        }
    },
    components: { CRCMDatatable },
}
</script>

<template>
    <h1 class="h1 text-center font-semibold uppercase">List of Items</h1>
    <CRCMDatatable
        :base-url="InventorySysPages.api.item.path"
        :base-model="InventorySysPages.api.item.model"
        :add-form="null"
        :edit-form="null"
    />
</template>

<style scoped>

</style>
