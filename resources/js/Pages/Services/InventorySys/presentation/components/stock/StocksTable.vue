<script>
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import { InventorySysPages } from "@/Pages/Services/InventorySys/components/components.js";
import QrCodeScanner from "@/Pages/Services/InventorySys/presentation/components/scanner/qr/QrCodeScanner.vue";
export default {
    name: "StocksTable",
    computed: {
        InventorySysPages() {
            return InventorySysPages
        },
    },
    components: {
        QrCodeScanner,
        CRCMDatatable,
    },
}
</script>

<template>
    <h1 class="h1 text-center font-semibold uppercase">Remaining Stocks</h1>
    <qr-code-scanner />
    <CRCMDatatable
        :base-url="InventorySysPages.api.stock.path"
        :base-model="InventorySysPages.api.stock.model"
        :add-form="null"
        :edit-form="null"
        :show-action-btns="false"
    />
</template>

