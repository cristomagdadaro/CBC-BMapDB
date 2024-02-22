<script>
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import { InventorySysPages } from "@/Pages/Services/InventorySys/components/components.js";

export default {
    name: "TransactionTable",
    computed: {
        InventorySysPages() {
            return InventorySysPages
        }
    },
    components: { CRCMDatatable },
}
</script>

<template>
    <h1 class="h1 text-center font-semibold uppercase">List of Transactions</h1>
    <CRCMDatatable
        :base-url="InventorySysPages.api.transaction.path"
        :base-model="InventorySysPages.api.transaction.model"
        :add-form="null"
        :edit-form="null"
    />
</template>

<style scoped>

</style>
