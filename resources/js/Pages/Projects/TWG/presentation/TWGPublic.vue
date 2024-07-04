<script setup>
import {Head} from "@inertiajs/vue3";
import Map from "@/Pages/Projects/BreedersMap/presentation/components/map/Map.vue";
import PageLayout from "@/Layouts/PageLayout.vue";
import SearchBy from "@/Components/CRCMDatatable/Components/SearchBy.vue";
import SearchBox from "@/Components/CRCMDatatable/Components/SearchBox.vue";
import TWGCard from "@/Pages/Projects/TWG/presentation/components/TWGCard.vue";

</script>

<template>
    <Head title="Breeder's Map" />
    <page-layout :isWideDisplay="false">
        <div class="min-h-screen sm:p-4 p-1 bg-white flex flex-col gap-2">
            <div>
                <h1 class="text-2xl font-medium font-monospace">
                    Biotechnology Technical Working Group
                </h1>
                <p class="leading-tight text-justify">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores debitis ducimus facere laborum minus nemo nihil nostrum quod saepe sed!
                </p>
            </div>
            {{ $page.props }}
            <div class="flex flex-col gap-3">
                <div class="flex gap-2 justify-end">
                    <search-by />
                    <search-box />
                </div>
                <div class="flex flex-wrap justify-evenly gap-2">
                    <TWGCard v-for="i in 15" />
                </div>
            </div>
        </div>
    </page-layout>
</template>

<style scoped>

</style>
