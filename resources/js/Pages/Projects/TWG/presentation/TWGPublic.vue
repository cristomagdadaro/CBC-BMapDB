<script>
import { Head } from "@inertiajs/vue3";
import BreadCrumb from "@/Components/BreadCrumb.vue";
import HeroImageParticlesBackground from "@/Components/HeroImageParticlesBackground.vue";
import TWGCard from "@/Pages/Projects/TWG/presentation/components/TWGCard.vue";
import TWGApiService from "@/Pages/Projects/TWG/infrastructure/TWGApiService.js";

export default {
    name: "TWGPublic",
    components: {
        BreadCrumb,
        TWGCard,
        Head,
        HeroImageParticlesBackground,
    },
    data() {
        return {
            apiService: null,
            baseURL: route("api.twg.summary.public"),
            lastUpdated: null,
        };
    },
    async mounted() {
        this.apiService = new TWGApiService(this.baseURL);
        await this.apiService.init();
        this.lastUpdated = new Date().toISOString();
    },
    methods: {
        async refresh() {
            if (this.apiService && !this.apiService.processing) {
                await this.apiService.refresh();
                this.lastUpdated = new Date().toISOString();
            }
        },
    },
    computed: {
        data() {
            if (this.apiService && this.apiService.response)
                return this.apiService.response.data;
            return [];
        },
        loading() {
            return this.apiService ? this.apiService?.api?.processing : true;
        },
    },
};
</script>

<template>
    <Head title="Biotech TWG Database" />
    <div
        class="bg-pin-gray min-h-screen flex flex-col z-50 overflow-hidden relative"
    >
        <bread-crumb class="z-50" />
        <div class="section-padding z-50">
            <div class="flex items-center gap-3 mb-2 px-2">
                <img
                    src="/img/logos/biotwg.png"
                    alt="Biotech TWG Database Logo"
                    class="w-auto h-12"
                    loading="lazy"
                />
                <div class="drop-shadow">
                    <h1 class="text-3xl font-bold text-gray-100 font-display">
                        Biotech TWG Database
                    </h1>
                    <p class="text-sm text-gray-300">
                        Comprehensive biotechnology studies across the
                        Philippines
                    </p>
                </div>
            </div>
            <div class="backdrop-blur rounded-2xl p-6 shadow-lg">
                <p class="text-gray-300">
                    A curated, comprehensive list of biotechnology studies
                    across the Philippines. Browse and explore projects,
                    institutions, and experts shaping the country's biotech
                    landscape.
                </p>
                <p class="mt-3 text-sm text-gray-300 flex items-center gap-2">
                    <svg
                        class="w-4 h-4 text-pin-lime"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                    For a more comprehensive view of the TWG data, please login
                    or register.
                </p>
            </div>
        </div>
        <section class="flex-1 section-padding z-50 my-3">
            <div class="flex items-center justify-between mb-4">
                <p v-if="lastUpdated" class="text-xs text-gray-300">
                        Last refreshed {{ new Date(lastUpdated).toLocaleString() }}
                </p>
                <button
                    @click="refresh"
                    :disabled="loading"
                    class="bg-pin-lime rounded-md text-sm px-4 py-2 flex items-center"
                >
                    <svg
                        class="w-4 h-4 mr-2"
                        :class="{ 'animate-spin': loading }"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                        />
                        </svg>
                        Refresh
                </button>
            </div>
            <div
                v-if="loading"
                class="flex flex-col backdrop-blur items-center justify-center rounded-2xl border border-white/30 bg-white/10 p-10 text-white gap-3"
            >
                <div
                    class="animate-spin h-12 w-12 border-4 border-t-pin-green border-white rounded-full"
                ></div>
                <span class="text-sm text-gray-200"
                    >Fetching latest TWG entries…</span
                >
            </div>
            <div v-else>
                <p v-if="!data.length" class="text-gray-200 text-center py-10">
                    No public TWG summaries found yet.
                </p>
                <div
                    v-else
                    class="grid lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-4"
                >
                    <TWGCard v-for="item in data" :key="item.id" :data="item" />
                </div>
            </div>
        </section>
        <hero-image-particles-background
            id="twg-bg"
            :images="['/img/philrice-cbc-compound.jpg']"
            particles-id="header-particles-js"
        />
    </div>
</template>

<style scoped></style>
