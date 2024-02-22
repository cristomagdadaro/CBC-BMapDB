<script>
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import { InventorySysPages } from "@/Pages/Services/InventorySys/components/components.js";
export default {
    name: "StocksTable",
    computed: {
        InventorySysPages() {
            return InventorySysPages
        }
    },
    components: { CRCMDatatable },
}
</script>

<template>
    <h1 class="h1 text-center font-semibold uppercase">Remaining Stocks</h1>
    <CRCMDatatable
        :base-url="InventorySysPages.api.stock.path"
        :base-model="InventorySysPages.api.stock.model"
        :add-form="null"
        :edit-form="null"
        :show-action-btns="false"
    />
</template>

<style scoped>

</style>
