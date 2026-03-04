<script>
import {Head} from "@inertiajs/vue3";
import Map from "@/Pages/Projects/BreedersMap/presentation/components/map/Map.vue";
import PageLayout from "@/Layouts/PageLayout.vue";
import BreadCrumb from "@/Components/BreadCrumb.vue";
export default {
    components: {
        BreadCrumb,
        PageLayout,
        Map,
        Head
    }
}
</script>

<template>
    <Head title="Plant Breeders Map" />
    <div class="bg-pin-gray min-h-screen flex flex-col">
        <bread-crumb />
        <div class="section-padding py-6">
            <div class="container-custom">
                <div class="flex items-center gap-3 mb-6">
                    <img src="/img/logos/pbmap.svg" alt="PBMap" class="w-auto h-10" loading="lazy" />
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 font-display">Plant Breeders Map</h1>
                        <p class="text-sm text-gray-500">Geographic distribution of breeders and research institutions</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex-1">
            <Map />
        </div>
    </div>
</template>
