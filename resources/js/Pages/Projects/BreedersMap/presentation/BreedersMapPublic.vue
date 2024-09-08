<script>
import {Head, Link} from "@inertiajs/vue3";
import Map from "@/Pages/Projects/BreedersMap/presentation/components/map/Map.vue";
import PageLayout from "@/Layouts/PageLayout.vue";
import Commodity from "@/Pages/Projects/BreedersMap/domain/Commodity";
import Tab from "@/Components/Tab/Tab.vue";
import Summary from "@/Pages/Projects/BreedersMap/presentation/components/summary/Summary.vue";

export default {
    computed: {
        Commodity() {
            return Commodity
        }
    },
    components: {
        Head,
        Map,
        PageLayout,
        Commodity,
        Tab,
        Summary,
        Link
    },
    data() {
        return {
            tabs: [
                {
                    name: "tab1",
                    label: "Charts",
                    active: true,
                    route: { name: 'projects.breedersmap.chart.public' },

                },
                {
                    name: "tab2",
                    label: "Geo Map",
                    active: true,
                    route: { name: 'projects.breedersmap.geomap.public' },
                },
            ],
        }
    },
}
</script>

<template>
    <Head title="Breeders' Map" />
    <page-layout>
        <div id="bm-welcome-box"  class="text-gray-900 bg-cbc-olive-green flex flex-col sm:gap-1 gap-3 sm:p-5 p-8 sm:text-left text-center drop-shadow-lg rounded-md">
            <p class="flex items-center gap-2 font-medium sm:text-3xl text-xl sm:leading-relaxed leading-tight">
                <span>Welcome to the Breeders' Map</span> <button id="bm-qg-start" class="bg-gray-300 rounded py-0.5 px-2 text-sm">Quick Guide?</button>
            </p>
            <p class="leading-relaxed sm:text-left text-justify sm:text-lg text-sm">
                This specialized online platform offers a centralized repository of essential information meticulously curated to support your crop biotechnology research endeavors. Within this digital resource, you will find a comprehensive collection of data, tools, and resources designed to facilitate your scientific investigations, accelerate discoveries, and drive innovation in the field of crop biotechnology.
            </p>
            <p class="leading-relaxed sm:text-left text-justify sm:text-lg text-sm">
                For a more comprehensive information, <Link :href="route('register')" class="underline font-medium uppercase hover:text-cbc-dark-green">register now</Link> to access the full features of the Breeders' Map.
            </p>
        </div>
        <Tab :tabs="tabs" class="z-[999]">
            <template #tab1>
                <Summary />
            </template>
            <template #tab2>
                <Map :base-url="route('api.commodities.noPage.public')" :model="Commodity" />
            </template>
        </Tab>

    </page-layout>
</template>

<style scoped>

</style>
