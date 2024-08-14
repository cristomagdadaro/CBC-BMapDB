<template>
    <Head title="TWG Database" />
    <app-layout>
        <Tab :tabs="tabs" v-if="$page.props.auth.user">
            <template #tab0>
                <t-w-g-summary />
            </template>
            <template #tab1>
                <experts-table />
            </template>
            <template #tab2>
                <projects-table />
            </template>
            <template #tab3>
                <products-table />
            </template>
            <template #tab4>
                <services-table />
            </template>
        </Tab>
        <p v-else>Please login to view the data</p>
    </app-layout>
</template>
<script>
import { TWGPages } from "@/Pages/Projects/TWG/components/components.js";
import {Head} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import {defineAsyncComponent} from "vue";
import Tab from "@/Components/Tab/Tab.vue";
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";

const isWideDisplay = true;
export default {
    computed: {
        TWGPages() {
            return TWGPages
        }
    },
    components: {
        TWGSummary: defineAsyncComponent(
            () => import("@/Pages/Projects/TWG/presentation/components/summary/TWGSummary.vue")
        ),
        ProductsTable: defineAsyncComponent(
            () => import("@/Pages/Projects/TWG/presentation/components/product/ProductsTable.vue")
        ),
        ProjectsTable: defineAsyncComponent(
            () => import("@/Pages/Projects/TWG/presentation/components/project/ProjectsTable.vue")
        ),
        ExpertsTable: defineAsyncComponent(
            () => import("@/Pages/Projects/TWG/presentation/components/expert/ExpertsTable.vue")
        ),
        ServicesTable: defineAsyncComponent(
            () => import("@/Pages/Projects/TWG/presentation/components/service/ServicesTable.vue")
        ),
        Head,
        AppLayout,
        Tab,
        CRCMDatatable
    },
    data() {
        return {
            tabs: [
                {
                    name: "tab0",
                    label: "Summary",
                    active: true,
                    route: { name: 'projects.twg.summary' },
                },
                {
                    name: "tab1",
                    label: "Experts",
                    active: false,
                    route: { name: 'projects.twg.experts' },
                },
                {
                    name: "tab2",
                    label: "Projects",
                    active: false,
                    route: { name: 'projects.twg.projects' },
                },
                {
                    name: "tab3",
                    label: "Products",
                    active: false,
                    route: { name: 'projects.twg.products' },
                },
                {
                    name: "tab4",
                    label: "Services/Protocols",
                    active: false,
                    route: { name: 'projects.twg.services' },
                },
            ],
            isWideDisplay: isWideDisplay,
        };
    },
    methods: {
        isActiveTab(tab) {
            return this.activeTab === tab;
        },
        setActiveTab(tab) {
            this.activeTab.active = false;
            this.activeTab = tab;
            this.activeTab.active = true;
        },
    },
    mounted() {
        if (Array.isArray(this.tabs)) {
            this.activeTab = this.tabs.find((tab) => tab.active);
        } else {
            this.activeTab = this.tabs[0];
        }
    },
};
</script>
