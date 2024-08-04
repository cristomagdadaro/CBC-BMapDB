<script setup>
import PageLayout from "@/Layouts/PageLayout.vue";
import { CBCProjectsPublic } from "@/Pages/constants.ts";
import { Link, Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Databases" />
    <page-layout :is-wide-display="true">
        <div class="flex flex-col gap-5 text-gray-700 p-2 overflow-x-hidden">
            <div class="text-gray-100 bg-cbc-olive-green flex flex-col sm:gap-1 gap-3 sm:p-5 p-8 sm:text-left text-center drop-shadow-lg rounded-md">
                <p class="font-medium sm:text-3xl text-xl sm:leading-relaxed leading-tight">
                    Welcome to
                    <br class="block sm:hidden" />
                    <span class="text-cbc-dark-green">C</span>rop<span class="text-cbc-dark-green">B</span>iotech
                    <span class="text-cbc-dark-green">C</span>entralized <span class="text-cbc-dark-green">D</span>atabase
                </p>
                <p class="leading-relaxed sm:text-left text-justify sm:text-lg text-sm">
                    This specialized online platform offers a centralized repository of essential information meticulously curated to support your crop biotechnology research endeavors. Within this digital resource, you will find a comprehensive collection of data, tools, and resources designed to facilitate your scientific investigations, accelerate discoveries, and drive innovation in the field of crop biotechnology.
                </p>
            </div>
            <div class="flex flex-wrap gap-2 p-2 sm:justify-start justify-center">
                <Link v-for="project in CBCProjectsPublic" :href="route(project.value)" class="shadow-md hover:bg-cbc-yellow-green text-gray-700 bg-cbc-yellow px-5 py-3 rounded">
                    {{ project.label }}
                </Link>
            </div>
        </div>
    </page-layout>
</template>

