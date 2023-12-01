<script setup>
import {Head} from "@inertiajs/vue3";
import Map from "@/Pages/Projects/BreedersMap/presentation/components/map/Map.vue";
import PageLayout from "@/Layouts/PageLayout.vue";
</script>

<template>
    <Head title="Breeder's Map" />
    <page-layout >
        <div class="min-h-screen sm:p-4 p-1 bg-white">
            <div>
                <h1 class="text-2xl font-medium font-monospace">
                    Breeder's Maps Database System
                </h1>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur dignissimos error, est in perspiciatis praesentium quos rerum! Iure maiores, maxime!
                </p>
            </div>
            <Map />
        </div>
    </page-layout>
</template>

<style scoped>

</style>
