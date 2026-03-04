<script>
import {Head} from "@inertiajs/vue3";
import PageLayout from "@/Layouts/PageLayout.vue";
import SearchBy from "@/Components/CRCMDatatable/Components/SearchBy.vue";
import SearchBox from "@/Components/CRCMDatatable/Components/SearchBox.vue";
import TWGCard from "@/Pages/Projects/TWG/presentation/components/TWGCard.vue";
import TWGApiService from "@/Pages/Projects/TWG/infrastructure/TWGApiService.js";
import {CBCProjectsPublic} from "../../../constants";
import BreadCrumb from "@/Components/BreadCrumb.vue";
import DashboardShell from "@/Pages/Dashboard/components/DashboardShell.vue";

export default {
    name: "TWGPublic",
    components: {
        DashboardShell,
        BreadCrumb,
        PageLayout,
        SearchBy,
        SearchBox,
        TWGCard,
        Head
    },
    data() {
        return {
            apiService: null,
            baseURL: route('api.twg.summary.public'),
            lastUpdated: null
        };
    },
    async mounted() {
        this.apiService = new TWGApiService(this.baseURL);
        await this.apiService.init();
        this.lastUpdated = new Date().toISOString();
    },
    methods: {
        CBCProjectsPublic() {
            return CBCProjectsPublic
        },
        async refresh() {
            if (this.apiService && !this.apiService.processing) {
                await this.apiService.refresh();
                this.lastUpdated = new Date().toISOString();
            }
        }
    },
    computed: {
        data() {
            if (this.apiService && this.apiService.response)
                return this.apiService.response.data;
            return [];
        },
        loading() {
            return this.apiService ? this.apiService?.api?.processing : true;
        }
    }
}
</script>

<template>
    <Head title="Biotech TWG Database" />
    <bread-crumb />
    <div class="section-padding py-8">
        <div class="container-custom">
            <div class="flex flex-col sm:flex-row items-center gap-4 mb-6">
                <img src="/img/logos/biotwg.png" alt="Biotech TWG Database Logo" class="w-auto h-16" loading="lazy" />
                <div class="text-center sm:text-left">
                    <h1 class="text-3xl font-bold text-gray-900 font-display">Biotech TWG Database</h1>
                    <p class="text-gray-500 mt-1">Comprehensive biotechnology studies across the Philippines</p>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm mb-8">
                <p class="text-gray-600">
                    A curated, comprehensive list of biotechnology studies across the Philippines. Browse and search to discover projects, institutions, and experts contributing to the country's biotech landscape.
                </p>
                <p class="mt-3 text-sm text-gray-500 flex items-center gap-2">
                    <svg class="w-4 h-4 text-pin-green" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    For a more comprehensive view of the TWG data, please login or register.
                </p>
            </div>
        </div>
    </div>
    <DashboardShell
        title="Biotech TWG Database"
        hide-header
        :isLoading="loading"
        :lastUpdated="lastUpdated"
        @refresh="refresh"
    >
        <div v-if="apiService" class="section-padding py-6">
            <div class="container-custom">
                <div v-if="apiService.request" class="flex hidden gap-2 justify-start mb-4 pb-4 border-b border-gray-200">
                    <search-by :value="apiService.request.getFilter"
                               :is-exact="apiService.request.getIsExact"
                               :options="[]"
                               @isExact="apiService.isExactFilter({ is_exact: $event })"
                               @searchBy=""/>
                    <search-box />
                </div>
                <div class="grid lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-4">
                    <TWGCard v-for="item in data" :key="item.id" :data="item" />
                </div>
            </div>
        </div>
    </DashboardShell>

</template>

<style scoped>

</style>
