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

export default {
    name: "TWGPublic",
    components: {
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
        };
    },
    async mounted() {
        this.apiService = new TWGApiService(this.baseURL);
        await this.apiService.init();
    },
    methods: {
        CBCProjectsPublic() {
            return CBCProjectsPublic
        },
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
            return this.data.affiliation;
        }
    }
}
</script>

<template>
    <Head title="Plant Breeders Map" />
    <page-layout>
        <green-waves />
        <public-page-section :animation="false">
            <div class="drop-shadow-md flex flex-col gap-5 shadow-lg bg-cbc-yellow mb-10 rounded-md sm:gap-1 sm:p-8 p-5 sm:text-left text-center">
                <div class="text-cbc-brown">
                    <img src="/img/logos/biotwg.png" alt="Biotech TWG Database Logo" class="mx-auto w-auto h-[8rem] drop-shadow-md"/>
                    <p class="text-normal text-justify">
                        For a more comprehensive view of the TWG data, please login or register to the system.
                    </p>
                </div>
                <div v-if="apiService" class="flex flex-col gap-3">
                    <div v-if="apiService.request" class="hidden flex gap-2 justify-start sm:mb-2 mb-1 pb-4 border-b">
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
            </div>
        </public-page-section>
    </page-layout>
</template>

<style scoped>

</style>
