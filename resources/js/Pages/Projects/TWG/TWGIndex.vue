<template>
    <Head title="TWG Database" />
    <AppLayout :is-wide-display="isWideDisplay">
        <div class="p-5">
            <Tab :tabs="tabs" v-if="$page.props.auth.user">
                <template #tab1>
                    Tab 1
                </template>
                <template #tab2>
                    Tab 2
                </template>
                <template #tab3>
                    Tab 3
                </template>
            </Tab>
            <p v-else>Please login to view the data</p>
        </div>
    </AppLayout>
</template>
<script setup>
import DataTable from "@/Components/DataTable/DataTable.vue";
import {Head} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import TWGCreateProject from "@/Pages/Projects/TWG/TWGFormProject.vue";
import {markRaw} from "vue";
import Tab from "@/Components/Tab/Tab.vue";
const isWideDisplay = true;

const tabs = [
    {
        name: "tab1",
        label: "Projects",
        active: true,
    },
    {
        name: "tab2",
        label: "Products",
        active: false,
    },
    {
        name: "tab3",
        label: "Services",
        active: false,
    },
]
</script>
