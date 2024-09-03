<script>
import {Head} from "@inertiajs/vue3";
import Map from "@/Pages/Projects/BreedersMap/presentation/components/map/Map.vue";
import PageLayout from "@/Layouts/PageLayout.vue";
import Commodity from "@/Pages/Projects/BreedersMap/domain/Commodity";
import Tab from "@/Components/Tab/Tab.vue";
import Summary from "@/Pages/Projects/BreedersMap/presentation/components/summary/Summary.vue";

export default {
    components: {
        Head,
        Map,
        PageLayout,
        Commodity,
        Tab,
        Summary
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
        <div class="shadow-lg bg-gray-100 rounded-md sm:p-4 p-1 flex flex-col gap-2">
            <div>
                <h1 class="text-2xl font-medium font-monospace">
                    Welcome to the Breeders' Map
                </h1>
                <p>
                    This interactive platform provides a comprehensive overview of the Philippines' biotechnology-driven plant breeding community. Locate breeders specializing in various priority commodities and explore their expertise in applying cutting-edge biotechnological techniques to develop improved crop varieties. This map serves as a valuable tool for collaboration, knowledge sharing, and advancing agricultural innovation through biotechnology.
                </p>
                <div>
                    <button class="bg-gray-300 rounded py-0.5 px-2 text-sm">Quick Guide?</button>
                </div>
            </div>
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
