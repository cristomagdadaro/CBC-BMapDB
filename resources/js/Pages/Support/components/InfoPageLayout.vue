<script>
import InfoPageLinks from "@/Pages/Support/components/InfoPageLinks.vue";
import {Head} from "@inertiajs/vue3";
import PageLayout from "@/Layouts/PageLayout.vue";
import PublicPageSection from "@/Layouts/components/PublicPageSection.vue";
import GreenWaves from "@/Components/GreenWaves.vue";

export default {
    name: "InfoPageLayout",
    components: {GreenWaves, PublicPageSection, PageLayout, Head, InfoPageLinks},
    props: {
        title: {
            type: String,
            required: true
        },

    }
}
</script>

<template>
    <Head :title="title" />
    <page-layout>
        <green-waves class="z-0" />
        <public-page-section :animation="false">
            <div class="flex gap-2 min-h-screen">
                <div class="flex flex-col gap-2 px-5 mr-5 border-r border-gray-100 z-10">
                    <info-page-links />
                </div>
                <div class="text-gray-900 bg-gray-50 flex flex-col w-full sm:gap-1 gap-3 sm:p-5 p-8 sm:text-left text-center drop-shadow-lg rounded-md">
                    <p class="flex items-center gap-2 font-medium sm:text-3xl text-xl sm:leading-relaxed leading-tight">
                        <span>{{ title }}</span>
                    </p>
                    <p class="leading-relaxed sm:text-left text-justify sm:text-lg text-sm">
                        <slot />
                    </p>
                </div>
            </div>
        </public-page-section>
    </page-layout>
</template>

<style scoped>

</style>
