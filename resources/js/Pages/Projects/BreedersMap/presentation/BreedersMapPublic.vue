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
    <div class="bg-pin-gray min-h-screen flex flex-col z-50 overflow-hidden relative">
        <bread-crumb class="z-50" />
        <div class="section-padding z-50">
            <div class="flex items-center gap-3 mb-2 px-2">
                <img src="/img/logos/pbmap.svg" alt="PBMap" class="w-auto h-10" loading="lazy" />
                <div class="drop-shadow">
                    <h1 class="text-2xl font-bold text-gray-100 font-display">Plant Breeders Map</h1>
                    <p class="text-sm text-gray-300">Geographic distribution of breeders and research institutions</p>
                </div>
            </div>
        </div>
        <div class="flex-1 z-50 section-padding ">
            <Map />
        </div>
        <hero-image-particles-background
            id="breeders-map-bg"
            :images="['/img/philrice-cbc-compound.jpg']"
            particles-id="header-particles-js"
        />
    </div>
</template>
