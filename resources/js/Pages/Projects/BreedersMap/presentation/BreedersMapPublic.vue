<script>
import {Head} from "@inertiajs/vue3";
import Map from "@/Pages/Projects/BreedersMap/presentation/components/map/Map.vue";
import PageLayout from "@/Layouts/PageLayout.vue";
import GreenWaves from "@/Components/GreenWaves.vue";
import PublicPageSection from "@/Layouts/components/PublicPageSection.vue";
import BreadCrumb from "@/Components/BreadCrumb.vue";
export default {
    components: {
        BreadCrumb,
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
    <div class="bg-gray-50 min-h-screen flex flex-col">
        <bread-crumb />
        <Map />
    </div>
</template>
