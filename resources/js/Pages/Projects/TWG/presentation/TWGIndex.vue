<template>
    <Head title="TWG Database" />
    <app-layout>
        <div class="min-h-screen sm:p-3 p-0.5 bg-transparent">
            <Tab :tabs="tabs" v-if="$page.props.auth.user">
                <template #tab1>
                    <div class="p-2">
                        <h1 class="h1 text-center font-semibold uppercase">Experts</h1>
                        <CRCMDatatable
                            :base-url="TWGPages.api.expert.path"
                            :base-model="TWGPages.api.expert.model"
                            :add-form="TWGPages.api.expert.create"
                            :edit-form="TWGPages.api.expert.edit"
                        />
                    </div>
                </template>
                <template #tab2>
                    <div class="p-2">
                        <h1 class="h1 text-center font-semibold uppercase">Projects</h1>
                        <CRCMDatatable
                            :base-url="TWGPages.api.project.path"
                            :base-model="TWGPages.api.project.model"
                        />
                    </div>
                </template>
                <template #tab3>
                    <div class="p-2">
                        <h1 class="h1 text-center font-semibold uppercase">Products</h1>
                        <CRCMDatatable
                            :base-url="TWGPages.api.product.path"
                            :base-model="TWGPages.api.product.model"
                        />
                    </div>
                </template>
                <template #tab4>
                    <div class="p-2">
                        <h1 class="h1 text-center font-semibold uppercase">Services/Protocols</h1>
                        <CRCMDatatable
                            :base-url="TWGPages.api.service.path"
                            :base-model="TWGPages.api.service.model"
                        />
                    </div>
                </template>
            </Tab>
            <p v-else>Please login to view the data</p>
        </div>
    </app-layout>
</template>
<script>
import DataTable from "@/Components/DataTable/DataTable.vue";
import { TWGPages } from "@/Pages/Projects/TWG/components/components.js";
import {Head} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import TWGCreateProject from "@/Pages/Projects/TWG/presentation/components/project/TWGFormProject.vue";
import {defineAsyncComponent, markRaw} from "vue";
import Tab from "@/Components/Tab/Tab.vue";
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
const isWideDisplay = true;
export default {
    computed: {
        TWGPages() {
            return TWGPages
        }
    },
    components: {DataTable, Head, AppLayout, Tab, CRCMDatatable},
    data() {
        return {
            tabs: [
                {
                    name: "tab1",
                    label: "Experts",
                    active: true,
                },
                {
                    name: "tab2",
                    label: "Projects",
                    active: false,
                },
                {
                    name: "tab3",
                    label: "Products",
                    active: false,
                },
                {
                    name: "tab4",
                    label: "Services/Protocols",
                    active: false,
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
