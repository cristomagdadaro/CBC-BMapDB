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

export default {
    components: {
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
    <Head title="Databases" />
    <page-layout>
        <div class="flex flex-col gap-10 sm:gap-16 md:gap-22 lg:gap-26 xl:gap-32 w-full bg-transparent">
            <green-waves />
            <public-page-section>
                <div class="text-light-color bg-cbc-olive-green flex flex-col sm:p-5 p-8 sm:text-left text-center drop-shadow-lg rounded-md">
                    <p class="text-title">
                        Welcome to
                        <br class="block sm:hidden" />
                        <span class="text-cbc-dark-green">C</span>rop <span class="text-cbc-dark-green">B</span>iotech
                        <span class="text-cbc-dark-green">C</span>entralized <span class="text-cbc-dark-green">D</span>atabase
                    </p>
                    <p class="paragraph-text-normal">
                        This specialized online platform offers a centralized repository of essential information meticulously curated to support your crop biotechnology research endeavors. Within this digital resource, you will find a comprehensive collection of data, tools, and resources designed to facilitate your scientific investigations, accelerate discoveries, and drive innovation in the field of crop biotechnology.
                    </p>
                </div>
            </public-page-section>
            <public-page-section>
                <bm-overview-map />
            </public-page-section>
            <public-page-section class="bg-gray-200">
                <bm-priority-com />
            </public-page-section>
            <public-page-section>
                <bm-database-list />
            </public-page-section>
            <public-page-section class="bg-gray-200">
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
