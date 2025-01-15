<script>
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Map from "@/Pages/Projects/BreedersMap/presentation/components/map/Map.vue";
import CommodityTable from "@/Pages/Projects/BreedersMap/presentation/components/commodity/CommodityTable.vue";
export default {
    components: {Head, CommodityTable, AppLayout, Map},
    props: {
        commodity: {
            type: Object,
            required: false,
            default: null,
        }
    },
}
</script>

<template>
    <Head title="Plant Breeders Map" />
    <app-layout>
    <div class="p-5">
        {{ commodity }}
        <h1 class="h1 text-center font-semibold uppercase select-none">Commodity Geo Location</h1>
        <Map v-if="commodity" :customPoint="commodity" />
    </div>
    </app-layout>

</template>

<style scoped>
#map {
    @apply sm:w-[50%] w-full h-screen z-0;
}
</style>
