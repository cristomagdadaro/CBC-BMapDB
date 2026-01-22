<script>
import {Head} from "@inertiajs/vue3";
import PageLayout from "@/Layouts/PageLayout.vue";
import SearchBy from "@/Components/CRCMDatatable/Components/SearchBy.vue";
import SearchBox from "@/Components/CRCMDatatable/Components/SearchBox.vue";
import TWGCard from "@/Pages/Projects/TWG/presentation/components/TWGCard.vue";
import TWGApiService from "@/Pages/Projects/TWG/infrastructure/TWGApiService.js";
import PublicPageSection from "@/Layouts/components/PublicPageSection.vue";
import GreenWaves from "@/Components/GreenWaves.vue";
import {CBCProjectsPublic} from "../../../constants";
import BreadCrumb from "@/Components/BreadCrumb.vue";
import DashboardShell from "@/Pages/Dashboard/components/DashboardShell.vue";

export default {
    name: "TWGPublic",
    components: {
        DashboardShell,
        BreadCrumb,
        GreenWaves,
        PublicPageSection,
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
    <Head title="Plant Breeders Map" />
    <bread-crumb />
    <div class="flex flex-col gap-5 sm:gap-1 sm:p-8 p-5 sm:text-left text-center">
        <div class="text-cbc-brown">
            <div class="flex items-center justify-center">
                <img src="/img/logos/biotwg.png" alt="Biotech TWG Database Logo" class="w-auto h-[5rem]"/>
                <h1 class="lg:mt-4 text-3xl font-semibold text-center sm:text-left leading-none">Biotech TWG Database</h1>
            </div>
            <p class="mt-2 text-normal text-justify">
                A curated, comprehensive list of biotechnology studies across the Philippines. Browse and search to discover projects, institutions, and experts contributing to the country’s biotech landscape.
            </p>
            <p class="mt-2 text-normal opacity-90 text-justify">
                For a more comprehensive view of the TWG data, please login or register.
            </p>
        </div>
    </div>
    <DashboardShell
        title="Biotech TWG Database"
        hide-header
        :isLoading="loading"
        :lastUpdated="lastUpdated"
        @refresh="refresh"
    >
        <div v-if="apiService" class="flex flex-col sm:gap-1 sm:p-8 p-5 sm:text-left text-center">
            <div v-if="apiService.request" class="flex hidden gap-2 justify-start sm:mb-2 mb-1 pb-4 border-b">
                <search-by :value="apiService.request.getFilter"
                           :is-exact="apiService.request.getIsExact"
                           :options="[]"
                           @isExact="apiService.isExactFilter({ is_exact: $event })"
                           @searchBy=""/>
                <search-box />
            </div>
            <div class="grid lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2  grid-cols-1 justify-evenly gap-2">
                <TWGCard v-for="item in data" :data="item" />
            </div>
        </div>
    </DashboardShell>

</template>

<style scoped>

</style>
