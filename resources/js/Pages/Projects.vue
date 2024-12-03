<script>
import PageLayout from "@/Layouts/PageLayout.vue";
import { CBCProjectsPublic } from "@/Pages/constants.ts";
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
    },
    setup() {
        return {
            CBCProjectsPublic,
        };
    },
};
</script>

<template>
    <Head :title="$page.props.appNameShort" />
    <page-layout>
        <green-waves />
<!--        <div class="flex flex-col gap-10 sm:gap-16 md:gap-22 lg:gap-26 xl:gap-32 w-full bg-transparent">-->
        <div class="grid grid-cols-1 grid-rows-6 w-full bg-transparent">
            <public-page-section class="flex items-center justify-center">
                <div class="flex relative items-end">
                    <div class="text-cbc-brown flex flex-col gap-3 sm:p-5 p-8 sm:text-left text-center drop-shadow-lg rounded-md">
                        <div class="text-title leading-tight">
                            <div class="text-2xl font-light">Welcome to</div>
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
                            This specialized online platform offers a centralized repository of essential information meticulously curated to support your crop biotechnology research endeavors. Within this digital resource, you will find a comprehensive collection of data, tools, and resources designed to facilitate your scientific investigations, accelerate discoveries, and drive innovation in the field of crop biotechnology.
                        </p>
                    </div>
                    <top-leaf class="w-3/4 h-[33rem] drop-shadow-lg" />
                </div>
            </public-page-section>
            <public-page-section>
                <bm-overview-map />
            </public-page-section>
            <public-page-section class="bg-gray-200 py-10">
                <bm-priority-com />
            </public-page-section>
            <public-page-section>
                <bm-database-list />
            </public-page-section>
            <public-page-section class="bg-gray-200 py-10">
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
</style>
