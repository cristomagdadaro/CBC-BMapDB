<script>
import {Head} from "@inertiajs/vue3";
import PageLayout from "@/Layouts/PageLayout.vue";
import SearchBy from "@/Components/CRCMDatatable/Components/SearchBy.vue";
import SearchBox from "@/Components/CRCMDatatable/Components/SearchBox.vue";
import TWGCard from "@/Pages/Projects/TWG/presentation/components/TWGCard.vue";
import TWGApiService from "@/Pages/Projects/TWG/infrastructure/TWGApiService.js";

export default {
    name: "TWGPublic",
    components: {
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
        };
    },
    async mounted() {
        this.apiService = new TWGApiService(this.baseURL);
        await this.apiService.init();
    },
    methods: {
        async refresh() {
            if (this.apiService && !this.apiService.processing)
                await this.apiService.refresh();
        }
    },
    computed: {
        data() {
            if (this.apiService && this.apiService.response)
                return this.apiService.response.data;
            return [];
        },
        affiliation() {
            //only the affiliation key in the data
            return this.data.affiliation;
        }
    }
}
</script>

<template>
    <Head title="Breeder's Map" />
    <page-layout :isWideDisplay="false">
        <div class="min-h-screen sm:p-4 p-1 bg-white flex flex-col gap-2">
            <div>
                <h1 class="text-2xl font-medium font-monospace">
                    Welcome to the TWG Database.
                </h1>
                <p class="leading-tight text-justify">
                    This centralized repository houses comprehensive information on biotechnology-related funded projects undertaken by different institutes, state universities, and regional field offices. Explore this database to access valuable insights, project details, and collaborative opportunities within the biotechnology research community.
                </p>
            </div>
            <div v-if="apiService" class="flex flex-col gap-3">
                <div v-if="apiService.request && apiService.request.params" class="hidden flex gap-2 justify-end">
                    <search-by :value="apiService.request.params.filter"
                               :is-exact="apiService.request.params.is_exact"
                               :options="[]"
                               @isExact="apiService.isExactFilter({ is_exact: $event })"
                               @searchBy="apiService.filterByColumn({ column: $event })"/>
                    <search-box />
                </div>
                <div class="grid lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2  grid-cols-1 justify-evenly gap-2">
                    <TWGCard v-for="item in data" :data="item" />
                </div>
            </div>
        </div>
    </page-layout>
</template>

<style scoped>

</style>
