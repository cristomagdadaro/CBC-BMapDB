<script setup>
import {Head} from "@inertiajs/vue3";
import Map from "@/Pages/Projects/BreedersMap/presentation/components/map/Map.vue";
import PageLayout from "@/Layouts/PageLayout.vue";
</script>

<template>
    <Head title="Breeder's Map" />
    <page-layout >
        <div class="min-h-screen sm:p-4 p-1 bg-white flex flex-col gap-2">
            <div>
                <h1 class="text-2xl font-medium font-monospace">
                    Breeder's Maps Database System
                </h1>
                <p>

                </p>
                <div>
                    <button class="bg-gray-300 rounded py-0.5 px-2 text-sm">Quick Guide?</button>
                </div>
            </div>
            <Map />
        </div>
    </page-layout>
</template>

<style scoped>

</style>
