<script>
import PageLayout from "@/Layouts/PageLayout.vue";
import {CBCProjectsPublic, companyName } from "@/Pages/constants.ts";
import { Link, Head } from '@inertiajs/vue3';
import PhilippineMapOutline from "@/Components/Icons/PhilippineMapOutline.vue";
import BmCollaborators from "@/Pages/Projects/BreedersMap/presentation/components/misc/BmCollaborators.vue";
import TransitionContainer from "@/Components/CustomDropdown/Components/TransitionContainer.vue";
import ArrowLeft from "@/Components/Icons/ArrowLeft.vue";
import ArrowRight from "@/Components/Icons/ArrowRight.vue";
import PublicPageSection from "@/Layouts/components/PublicPageSection.vue";
import GreenWaves from "@/Components/GreenWaves.vue";
import BmDatabaseList from "@/Pages/Projects/BreedersMap/presentation/components/misc/BmDatabaseList.vue";
import BmPriorityCom from "@/Pages/Projects/BreedersMap/presentation/components/misc/BmPriorityCom.vue";
import BmOverviewMap from "@/Pages/Projects/BreedersMap/presentation/components/misc/BmOverviewMap.vue";
import TopLeaf from "@/Components/Top-Leaf.vue";

export default {
    components: {
        TopLeaf,
        BmOverviewMap,
        BmPriorityCom,
        BmDatabaseList,
        GreenWaves,
        PublicPageSection,
        ArrowRight,
        ArrowLeft,
        TransitionContainer,
        BmCollaborators,
        PageLayout,
        Link,
        Head,
        PhilippineMapOutline,
        companyName
    },
    methods: {
        companyName() {
            return companyName
        },
        setupParticlesFor(id)  {
            particlesJS.load(id, '/particlesjs-config.json', function() {
                console.log('particles for Projects Page loaded');
            });
        },
    },
    mounted() {
        this.setupParticlesFor('header-particles-js');
        //this.setupParticlesFor('center-particles-js');
        this.setupParticlesFor('foot-particles-js');
    },
    setup() {
        return {
            CBCProjectsPublic,
        };
    },
};
</script>

<template>
    <Head title="Home" />
    <page-layout>
        <green-waves />
        <div class="grid grid-cols-1 body-grid-rows w-full bg-transparent">
            <public-page-section onload="" class="flex items-center justify-center">
                <template v-slot:custom-bg>
                    <div id="header-particles-js" class="absolute top-0 left-0 w-full h-full -z-[999]"></div>
                </template>
                <div class="flex justify-center">
                    <div class="text-cbc-brown flex flex-col gap-3 sm:p-5 p-8 text-left drop-shadow-lg rounded-md">
                        <div class="text-title leading-tight">
                            <div class="sm:text-2xl text-lg  font-light">Welcome to</div>
                            <div class="leading-tight">
                                <div>
                                    <span class="text-cbc-olive-green drop-shadow">P</span>lant&nbsp;Breeders and
                                </div>
                                <div>
                                    <span class="text-cbc-olive-green drop-shadow">I</span>nnovators
                                    <span class="text-cbc-olive-green drop-shadow">N</span>etwork
                                </div>
                            </div>
                        </div>
                        <p class="text-normal drop-shadow text-justify">
                            Empowering crop biotechnology research with innovation, one discovery at a time.
                        </p>
                    </div>
                </div>
            </public-page-section>
            <public-page-section class="flex items-center text-center">
                <div class="flex flex-col gap-5 justify-between items-center">
                    <Link class="flex items-center drop-shadow-lg" :href="'/'">
                        <img src="/img/logos/pin.svg" class="h-auto w-[8rem] sm:w-[12rem] lg:w-[15rem] min-w-[40%]" alt="Plant Breeders Map Database Logo"/>
                    </Link>
                    <p class="text-normal text-dark-color">
                        This specialized online platform offers a centralized repository of essential information meticulously curated to support your crop biotechnology research endeavors. Within this digital resource, you will find a comprehensive collection of data, tools, and resources designed to facilitate your scientific investigations, accelerate discoveries, and drive innovation in the field of crop biotechnology.
                    </p>
                    <Link class="flex items-center h-auto w-[8rem] sm:w-[12rem] lg:w-[15rem] max-w-[15%] drop-shadow-lg" :href="'/'">
                        <img src="/img/logo-black.png" alt="DA-CBC Logo"/>
                    </Link>
                </div>
            </public-page-section>
            <public-page-section class="flex items-center">
                <template v-slot:custom-bg>
                    <div id="center-particles-js" class="absolute top-0 left-0 w-full h-full -z-[999]"></div>
                    <img src="/img/bg2.png"
                         alt="DA-CBC Building"
                         class="absolute top-0 left-0 w-full h-full z-[-1] brightness-50 object-cover object-top" />
                </template>
                <p class="text-subtitle text-center text-light-color">
                    {{ companyName() }}
                </p>
                <p class="text-normal text-center text-light-color">
                    The center is a premier hub for innovation and research in agricultural biotechnology. Dedicated to advancing crop productivity and sustainability, the center provides cutting-edge solutions, resources, and technologies to support farmers, researchers, and stakeholders in addressing the challenges of food security and agricultural development.
                </p>
            </public-page-section>
            <public-page-section class="flex items-center">
                <div class="grid grid-rows-2 grid-cols-1 md:grid-rows-1 md:grid-cols-2 gap-2 md:gap-5">
                    <Link class="flex justify-center md:justify-end drop-shadow-lg" :href="route(Object.values(CBCProjectsPublic)[0].value)">
                        <img class="h-auto" src="/img/logos/pbmap.svg" alt="Plant Breeders Map Database Logo"/>
                    </Link>
                    <div class="flex flex-col justify-center text-center md:text-left">
                        <Link :href="route(Object.values(CBCProjectsPublic)[0].value)" class="text-subtitle text-dark-color">
                            {{ Object.values(CBCProjectsPublic)[0].label }}
                        </Link>
                        <p class="text-normal text-dark-color text-justify">
                            {{ Object.values(CBCProjectsPublic)[0].description }}
                        </p>
                    </div>
                </div>
            </public-page-section>
            <public-page-section class="flex items-center py-10">
                <bm-overview-map />
            </public-page-section>
            <public-page-section class="flex items-center py-10">
                <bm-priority-com />
            </public-page-section>
            <public-page-section class="flex items-center text-center">
                <div class="flex flex-col gap-5 justify-between items-center">
                    <Link class="h-auto w-[40%] min-w-[20rem] drop-shadow-lg" :href="route(Object.values(CBCProjectsPublic)[1].value)">
                        <img src="/img/logos/biotwg.png" alt="Biotech TWG Database Logo"/>
                    </Link>
                    <p class="text-normal text-dark-color">
                        {{ Object.values(CBCProjectsPublic)[1].description }}
                    </p>
                </div>
            </public-page-section>
            <public-page-section class="flex items-center">
                <bm-database-list />
            </public-page-section>
            <public-page-section class="flex items-center py-10">
                <template v-slot:custom-bg>
                    <div id="foot-particles-js" class="absolute top-0 left-0 w-full h-full -z-[999]"></div>
                </template>
                <bm-collaborators />
            </public-page-section>
        </div>
    </page-layout>
</template>

<style scoped>
.flex-row {
    display: flex;
    flex-direction: row;
    overflow-x: auto;
    scroll-behavior: smooth;
    padding-left: 1rem;
    padding-right: 1rem;
}

.flex-row::-webkit-scrollbar {
    display: none;
}

.flex-row {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.body-grid-rows{
    grid-template-rows:repeat(9,minmax(0,1fr))
}

.body-grid-rows > *:nth-child(even) {
    @apply  bg-gray-200;
}
</style>
