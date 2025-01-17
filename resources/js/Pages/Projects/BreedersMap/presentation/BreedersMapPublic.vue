<script>
import {Head, Link} from "@inertiajs/vue3";
import Map from "@/Pages/Projects/BreedersMap/presentation/components/map/Map.vue";
import PageLayout from "@/Layouts/PageLayout.vue";
import Commodity from "@/Pages/Projects/BreedersMap/domain/Commodity";
import Tab from "@/Components/Tab/Tab.vue";
import Summary from "@/Pages/Projects/BreedersMap/presentation/components/summary/Summary.vue";
import PublicPageSection from "@/Layouts/components/PublicPageSection.vue";
import GreenWaves from "@/Components/GreenWaves.vue";
import InfoIcon from "@/Components/Icons/InfoIcon.vue";
import Breeder from "@/Pages/Projects/BreedersMap/domain/Breeder";
import {CBCProjectsPublic} from "../../../constants";

export default {
    computed: {
        Commodity() {
            return Commodity
        }
    },
    components: {
        InfoIcon,
        GreenWaves,
        PublicPageSection,
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
                    label: "Geo Map",
                    active: true,
                    route: { name: 'projects.breedersmap.geomap.public' },
                },
                {
                    name: "tab2",
                    label: "Charts",
                    active: true,
                    route: { name: 'projects.breedersmap.chart.public' },

                },
            ],
            tables: [
                { label: 'Commodities', name: 'commodities', route: route('api.breedersmap.commodities.summary.public'), model: Commodity },
                { label: 'Breeders', name: 'breeders', route: route('api.breedersmap.breeders.summary.public'), model: Breeder },
            ]
        }
    },
    methods: {
        CBCProjectsPublic() {
            return CBCProjectsPublic
        },
        fadeOut() {
            setTimeout(() => {
                const element = this.$refs.fadeOutElement;
                element.classList.add('fade-out');
                element.addEventListener('transitionend', () => {
                    element.classList.add('fade-out-hidden');
                }, { once: true });
            }, 10000);
        },
        fadeIn() {
            const element = this.$refs.fadeOutElement;
            element.classList.remove('fade-out-hidden');
            element.classList.remove('fade-out');
            this.fadeOut();
        }
    },
    mounted() {
        this.fadeOut();
    }
}
</script>

<template>
    <Head title="Plant Breeders Map" />
    <page-layout>
        <green-waves />
        <public-page-section :animation="false">
            <div id="bm-welcome-box"  class="flex flex-col sm:gap-1 gap-3 p-4 bg-white sm:text-left text-justify drop-shadow">
                <div class="flex items-center gap-2 justify-between text-subtitle">
                    <div class="flex items-center gap-2">
                        <span>{{ Object.values(CBCProjectsPublic())[0].label }}</span>
                        <info-icon @click="fadeIn" class="w-4 md:w-5 xl:w-6 h-auto cursor-pointer active:scale-90 duration-200" />
                    </div>
                    <button id="bm-qg-start" @click="fadeIn" class="bg-cbc-yellow text-dark-color py-1 px-2 text-normal z-[999]">Quick Guide?</button>
                </div>
                <div ref="fadeOutElement" >
                    <p class="text-normal">
                        This specialized online platform offers a centralized repository of essential information meticulously curated to support your crop biotechnology research endeavors. Within this digital resource, you will find a comprehensive collection of data, tools, and resources designed to facilitate your scientific investigations, accelerate discoveries, and drive innovation in the field of crop biotechnology.
                    </p>
                    <p class="text-normal">
                        For a more comprehensive information, <Link :href="route('register')" class="underline font-medium uppercase hover:text-cbc-yellow">register now</Link> to access the full features of PIN.
                    </p>
                </div>
            </div>
            <Tab :tabs="tabs">
                <template #tab1>
                    <Map :table-list="tables" :model="Commodity" />
                </template>
                <template #tab2>
                    <Summary :table-list="tables"  />
                </template>
            </Tab>
        </public-page-section>
    </page-layout>
</template>
