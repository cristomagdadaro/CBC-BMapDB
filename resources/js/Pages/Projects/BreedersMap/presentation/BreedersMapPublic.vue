<script setup>
import {Head} from "@inertiajs/vue3";
import Map from "@/Pages/Projects/BreedersMap/presentation/components/map/Map.vue";
import PageLayout from "@/Layouts/PageLayout.vue";
</script>

<template>
    <Head title="Breeder's Map" />
    <page-layout >
        <div class="min-h-screen sm:p-4 p-1 bg-white">
            <Map />
        </div>
    </page-layout>
</template>

<style scoped>

</style>
