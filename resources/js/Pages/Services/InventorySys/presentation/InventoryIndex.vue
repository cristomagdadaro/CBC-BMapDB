<script>
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Tab from "@/Components/Tab/Tab.vue";
import ItemsTable from "@/Pages/Services/InventorySys/presentation/components/item/ItemsTable.vue";
import TransactionTable from "@/Pages/Services/InventorySys/presentation/components/transaction/TransactionTable.vue";
import SupplierTable from "@/Pages/Services/InventorySys/presentation/components/supplier/SupplierTable.vue";
import StocksTable from "@/Pages/Services/InventorySys/presentation/components/stock/StocksTable.vue";
export default {
    name: "InventoryIndex",
    components: {SupplierTable, StocksTable, TransactionTable, ItemsTable, Tab, AppLayout, Head },
    data() {
        return {
            tabs: [
                {
                    name: "tab0",
                    label: "Stocks",
                    active: true,
                },
                {
                    name: "tab1",
                    label: "Items",
                    active: false,
                },
                {
                    name: "tab2",
                    label: "Transactions",
                    active: false,
                },
                {
                    name: "tab3",
                    label: "Suppliers",
                    active: false,
                },
            ]
        }
    }
}
</script>

<template>
    <Head title="TWG Database" />
    <app-layout>
        <Tab :tabs="tabs" v-if="$page.props.auth.user">
            <template #tab0>
                <stocks-table />
            </template>
            <template #tab1>
                <items-table />
            </template>
            <template #tab2>
                <transaction-table />
            </template>
            <template #tab3>
                <supplier-table />
            </template>
        </Tab>
        <p v-else>Please login to view the data</p>
    </app-layout>
</template>

<style scoped>

</style>
