<script setup>
import PageLayout from "@/Layouts/PageLayout.vue";
import { CBCProjectsPublic } from "@/Pages/constants.ts";
import { Link, Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Databases" />
    <page-layout :is-wide-display="true">
        <div class="flex flex-col gap-5 text-gray-700 p-2">
            <div class="bg-cbc-yellow p-5 drop-shadow-lg rounded-md">
                <p class="font-medium text-2xl leading-relaxed">
                    Welcome to CropBiotech Centralized Database
                </p>
                <p class="leading-relaxed">
                    This specialized online platform offers a centralized repository of essential information meticulously curated to support your crop biotechnology research endeavors. Within this digital resource, you will find a comprehensive collection of data, tools, and resources designed to facilitate your scientific investigations, accelerate discoveries, and drive innovation in the field of crop biotechnology.
                </p>
            </div>
            <div class="flex flex-wrap gap-2 p-2">
                <Link v-for="project in CBCProjectsPublic" :href="route(project.value)" class="shadow-md hover:bg-gray-600 text-gray-100 bg-gray-400 px-5 py-3 rounded">
                    {{ project.label }}
                </Link>
            </div>
        </div>
    </page-layout>
</template>

