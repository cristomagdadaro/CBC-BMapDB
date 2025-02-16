<script>
import PageLayout from "@/Layouts/PageLayout.vue";
import {CBCProjectsPublic } from "@/Pages/constants.ts";
import {Link, Head, usePage} from '@inertiajs/vue3';
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
import ModelViewer from "@/Components/3dViewer/3dModelViewer.vue";
import ParticlesBackground from "@/Components/ParticlesBackground.vue";

export default {
    components: {
        ParticlesBackground,
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
        ModelViewer
    },
    setup() {
        return {
            CBCProjectsPublic,
        };
    },
    data() {
        return {
            page: usePage(),
        }
    },
};
</script>

<template>
    <Head title="Welcome" />
    <page-layout>
        <green-waves />
        <div class="grid grid-cols-1 body-grid-rows w-full bg-transparent">
            <public-page-section onload="" class="flex items-center justify-center">
                <template v-slot:custom-bg>
                    <particles-background id="header-particles-js" />
                </template>
                <div class="flex flex-col justify-center">
                    <div class="text-cbc-brown flex flex-col gap-3 sm:p-5 p-8 text-left drop-shadow-lg rounded-md">
                        <div class="text-title leading-tight">
                            <h1 class="sm:text-2xl text-lg font-light">Welcome to</h1>
                            <div class="leading-tight text-title">
                                <h1 class="whitespace-nowrap">
                                    <span class="text-cbc-olive-green drop-shadow">P</span>lant&nbsp;Breeders and
                                </h1>
                                <h1>
                                    <span class="text-cbc-olive-green drop-shadow">I</span>nnovators
                                    <span class="text-cbc-olive-green drop-shadow">N</span>etwork
                                </h1>
                            </div>
                        </div>
                        <h1 class="text-normal drop-shadow text-justify px-1">
                            Empowering crop biotechnology research with innovation, one discovery at a time.
                        </h1>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-center items-center border-t">
                        <Link v-for="project in CBCProjectsPublic" :href="route(project.route_public)" class="flex flex-col lg:flex-row justify-center gap-1 items-center w-1/2 whitespace-nowrap text-cbc-brown bg-transparent text-center p-10  hover:text-gray-100 hover:drop-shadow-lg active:scale-95 duration-200 uppercase text-subtitle">
                            <img :src="project.logo" :alt="project.label" class="w-auto h-[5rem]">
                            <label class="text-subtitle">{{ project.label }}</label>
                        </Link>
                    </div>
                </div>
            </public-page-section>
            <public-page-section class="flex items-center text-center">
                <div class="flex flex-col gap-5 justify-between items-center">
                    <Link class="flex items-center h-auto w-[8rem] sm:w-[12rem] lg:w-[15rem] max-w-[15rem] drop-shadow-lg" :href="'/'">
                        <img src="/img/logo-black.png" alt="DA-CBC Logo"/>
                    </Link>
                    <p class="text-normal text-dark-color">
                        This specialized online platform offers a centralized repository of essential information meticulously curated to support your crop biotechnology research endeavors. Within this digital resource, you will find a comprehensive collection of data, tools, and resources designed to facilitate your scientific investigations, accelerate discoveries, and drive innovation in the field of crop biotechnology.
                    </p>
                    <Link class="flex items-center drop-shadow-lg" :href="'/'">
                        <img src="/img/logos/pin.svg" class="h-auto w-[8rem] sm:w-[12rem] lg:w-[15rem] min-w-[40%]" alt="Plant Breeders Map Database Logo"/>
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
                    {{ $companyName }}
                </p>
                <p class="text-normal text-center text-light-color">
                    The center is a premier hub for innovation and research in agricultural biotechnology. Dedicated to advancing crop productivity and sustainability, the center provides cutting-edge solutions, resources, and technologies to support farmers, researchers, and stakeholders in addressing the challenges of food security and agricultural development.
                </p>
            </public-page-section>
            <public-page-section class="flex items-center">
                <div class="grid grid-rows-2 grid-cols-1 md:grid-rows-1 md:grid-cols-2 gap-2 md:gap-5">
                    <Link class="flex justify-center md:justify-end drop-shadow-lg" :href="route(Object.values(CBCProjectsPublic)[0].route_public)">
                        <img class="h-auto w-[8rem] sm:w-[12rem] lg:w-[15rem] max-w-[15rem]" src="/img/logos/pbmap.svg" alt="Plant Breeders Map Database Logo"/>
                    </Link>
                    <div class="flex flex-col justify-center text-center md:text-left">
                        <Link :href="route(Object.values(CBCProjectsPublic)[0].route_public)" class="text-subtitle text-dark-color">
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
<!--            <public-page-section class="flex items-center py-10">
                <div class="relative flex flex-col">
                    <model-viewer model-url="/img/3d/corn.glb"/>
                    <label class="text-red-700 text-center text-xs">We will be adding more 3d images soon. Use Polycam or any mobile 3d object scanner app. Use iphones with LiDAR sensor.</label>
                    <div class="w-full p-5 lg:m-2 rounded drop-shadow text-sm">
                        <p class="text-justify"><span class="font-bold">Bt Corn </span> a genetically enhanced crop that has been engineered to produce a protein from the bacterium Bacillus thuringiensis (Bt). This protein is toxic to certain insect pests, such as the European corn borer, but harmless to humans, animals, and beneficial insects. By incorporating the Bt gene into the corn's DNA, the plant gains built-in resistance to specific pests, reducing the need for chemical insecticides. Bt corn has been widely adopted in agriculture due to its potential to increase crop yields, lower production costs, and reduce environmental impact. However, it has also sparked debates about its long-term effects on ecosystems, pest resistance, and food safety.</p>
                    </div>
                </div>
            </public-page-section>-->
            <public-page-section class="flex items-center py-10">
                <bm-priority-com />
            </public-page-section>
            <public-page-section class="flex items-center text-center">
                <div class="flex flex-col gap-5 justify-between items-center">
                    <Link class="flex justify-center md:justify-end drop-shadow-lg" :href="route(Object.values(CBCProjectsPublic)[1].route_public)">
                        <img class="h-auto w-[12rem] sm:w-[17rem] lg:w-[25rem] max-w-[25rem]" src="/img/logos/biotwg.full.png" alt="Biotech TWG Database Logo"/>
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
                    <particles-background id="foot-particles-js" />
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
