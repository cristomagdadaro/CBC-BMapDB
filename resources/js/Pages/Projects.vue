<script>
import PageLayout from "@/Layouts/PageLayout.vue";
import { CBCProjectsPublic } from "@/Pages/constants.ts";
import { Link, Head } from '@inertiajs/vue3';
import PhilippineMapOutline from "@/Components/Icons/PhilippineMapOutline.vue";

export default {
    components: {
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
    data() {
        return {
            hoveredData: null
        };
    },
};
</script>

<template>
    <Head title="Databases" />
    <page-layout :is-wide-display="true">
        <div class="flex flex-col gap-5 text-gray-700 p-2 overflow-x-hidden">
            <div class="text-gray-100 bg-cbc-olive-green flex flex-col sm:gap-1 gap-3 sm:p-5 p-8 sm:text-left text-center drop-shadow-lg rounded-md">
                <p class="font-medium sm:text-3xl text-xl sm:leading-relaxed leading-tight">
                    Welcome to
                    <br class="block sm:hidden" />
                    <span class="text-cbc-dark-green">C</span>rop<span class="text-cbc-dark-green">B</span>iotech
                    <span class="text-cbc-dark-green">C</span>entralized <span class="text-cbc-dark-green">D</span>atabase
                </p>
                <p class="leading-relaxed sm:text-left text-justify sm:text-lg text-sm">
                    This specialized online platform offers a centralized repository of essential information meticulously curated to support your crop biotechnology research endeavors. Within this digital resource, you will find a comprehensive collection of data, tools, and resources designed to facilitate your scientific investigations, accelerate discoveries, and drive innovation in the field of crop biotechnology.
                </p>
            </div>
            <div class="flex flex-wrap gap-2 p-2 sm:justify-start justify-center z-[999]">
                <Link v-for="project in CBCProjectsPublic" :href="route(project.value)" class="shadow-md hover:bg-cbc-yellow-green text-gray-700 bg-cbc-yellow px-5 py-3 rounded">
                    {{ project.label }}
                </Link>
            </div>
            <div>
                <h3 class="text-center select-none lg:text-[3rem] md:text-[2.2rem] sm:text-[1.7rem] text-[1rem] font-bold text-gray-900 drop-shadow">
                    Breeders' Map Overview
                </h3>
                <div class="flex justify-between items-center">
                    <div v-if="hoveredData" class="select-none lg:text-[3rem] md:text-[2.2rem] sm:text-[1.7rem] text-[1rem] font-bold text-gray-900 drop-shadow">
                        {{ hoveredData.province }} <br /> has a total of <br /> {{ hoveredData.data }} biotechnology <br /> related commodities
                    </div>
                    <div v-else class="text-[2rem] font-normal text-gray-900 drop-shadow">
                        Hover over a region to see data.
                    </div>
                    <philippine-map-outline class="max-w-[30vw] min-w-[7rem] drop-shadow-lg" @hovered="hoveredData = $event"/>
                </div>
            </div>
        </div>
    </page-layout>
</template>

