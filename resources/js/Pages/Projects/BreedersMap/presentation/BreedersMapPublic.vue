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
import TransitionContainer from "@/Components/CustomDropdown/Components/TransitionContainer.vue";

export default {
    computed: {
        Commodity() {
            return Commodity
        }
    },
    components: {
        TransitionContainer,
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
            ],
            infoShow: true,
        }
    },
    methods: {
        CBCProjectsPublic() {
            return CBCProjectsPublic
        },
        fadeOut() {
            setTimeout(() => {
                this.infoShow = false;
            }, 10000);
        },
        fadeToggle() {
            this.infoShow = !this.infoShow;
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
                        <div class="flex  items-center gap-2">
                            <img src="/img/logos/pbmap.webp" alt="Biotech TWG Database Logo" class="mx-auto w-auto h-[4rem] drop-shadow-md"/>
                            <span>{{ Object.values(CBCProjectsPublic())[0].label }}</span>
                        </div>
                        <info-icon @click="fadeIn" class="w-4 md:w-5 xl:w-6 h-auto cursor-pointer active:scale-90 duration-200" />
                    </div>
                    <button id="bm-qg-start" @click="fadeToggle" class="bg-cbc-yellow text-dark-color py-1 px-2 text-normal z-[999]">Quick Guide?</button>
                </div>
                <transition-container>
                   <div v-show="infoShow">
                       <p class="text-normal">
                           This specialized online platform offers a centralized repository of essential information meticulously curated to support your crop biotechnology research endeavors. Within this digital resource, you will find a comprehensive collection of data, tools, and resources designed to facilitate your scientific investigations, accelerate discoveries, and drive innovation in the field of crop biotechnology.
                       </p>
                       <p class="text-normal">
                           For a more comprehensive information, <Link :href="route('register')" class="underline font-medium uppercase hover:text-cbc-yellow">register now</Link> to access the full features of PIN.
                       </p>
                   </div>
                </transition-container>
            </div>
            <Tab :tabs="tabs">
                <template #tab1>
                    <Map :table-list="tables" :model="Commodity" :params="$page.props.params"/>
                </template>
                <template #tab2>
                    <Summary :table-list="tables"  />
                </template>
            </Tab>
        </public-page-section>
    </page-layout>
</template>
