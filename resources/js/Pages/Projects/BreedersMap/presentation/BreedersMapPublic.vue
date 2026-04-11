<script>
import {Head} from "@inertiajs/vue3";
import Map from "@/Pages/Projects/BreedersMap/presentation/components/map/Map.vue";
import PageLayout from "@/Layouts/PageLayout.vue";
import BreadCrumb from "@/Components/BreadCrumb.vue";
import HeroImageParticlesBackground from "@/Components/HeroImageParticlesBackground.vue";

export default {
    components: {
        BreadCrumb,
        PageLayout,
        Map,
        Head,
        HeroImageParticlesBackground,
    }
}
</script>

<template>
    <Head title="Plant Breeders Map" />
    <div class="bg-pin-gray h-screen flex flex-col z-50 overflow-hidden relative">
        <bread-crumb class="z-50 absolute top-4 left-4 bg-black/30 backdrop-blur-sm rounded-lg px-3 py-1" />

        <!-- Optional: Floating header overlay -->
        <div class="absolute top-4 left-1/2 -translate-x-1/2 z-50 bg-black/40 backdrop-blur-md rounded-full px-6 py-2 hidden md:flex items-center gap-3">
            <img src="/img/logos/pbmap.svg" alt="PBMap" class="w-auto h-6" loading="lazy" />
            <div>
                <h1 class="text-lg font-bold text-white font-display">Plant Breeders Map</h1>
            </div>
        </div>

        <!-- Map fills remaining space -->
        <div class="flex-1 z-40 h-full">
            <Map class="h-full" />
        </div>

        <hero-image-particles-background
            id="breeders-map-bg"
            :images="['/img/philrice-cbc-compound.jpg']"
            particles-id="header-particles-js"
        />
    </div>
</template>
