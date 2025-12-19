<script>
import {Head} from "@inertiajs/vue3";
import Map from "@/Pages/Projects/BreedersMap/presentation/components/map/Map.vue";
import PageLayout from "@/Layouts/PageLayout.vue";
import GreenWaves from "@/Components/GreenWaves.vue";
import PublicPageSection from "@/Layouts/components/PublicPageSection.vue";
export default {
    components: {
        PageLayout,
        Map,
        Head,
        GreenWaves,
        PublicPageSection
    }
}
</script>

<template>
    <Head title="Plant Breeders Map" />
    <page-layout>
        <green-waves />
        <public-page-section :animation="false">
            <Map />
        </public-page-section>
    </page-layout>
</template>
