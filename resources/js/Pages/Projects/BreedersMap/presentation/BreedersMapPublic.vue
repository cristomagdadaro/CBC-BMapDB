<script setup>
import {Head} from "@inertiajs/vue3";
import Map from "@/Pages/Projects/BreedersMap/presentation/components/map/Map.vue";
import PageLayout from "@/Layouts/PageLayout.vue";
import GreenWaves from "@/Components/GreenWaves.vue";
</script>

<template>
    <Head title="Breeder's Map" />
    <page-layout>
        <div class="shadow-lg bg-cbc-olive-green rounded-md mb-10 sm:p-4 p-1 flex flex-col gap-2">
            <div>
                <h1 class="text-2xl font-medium font-monospace">
                    Welcome to the Breeder's Map
                </h1>
                <p>
                    This interactive platform provides a comprehensive overview of the Philippines' biotechnology-driven plant breeding community. Locate breeders specializing in various priority commodities and explore their expertise in applying cutting-edge biotechnological techniques to develop improved crop varieties. This map serves as a valuable tool for collaboration, knowledge sharing, and advancing agricultural innovation through biotechnology.
                </p>
                <div>
                    <button class="bg-gray-300 rounded py-0.5 px-2 text-sm">Quick Guide?</button>
                </div>
            </div>
            <Map :base-url="route('api.commodities.noPage.public')" />
        </div>
    </page-layout>
</template>

<style scoped>

</style>
