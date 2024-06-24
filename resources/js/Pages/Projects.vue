<script setup>
import PageLayout from "@/Layouts/PageLayout.vue";
import { CBCProjectsPublic } from "@/Pages/constants.ts";
import { Link, Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Databases" />
    <PageLayout :is-wide-display="false">
        <div class="flex flex-wrap gap-2 p-2">
            <Link v-for="project in CBCProjectsPublic" :href="route(project.value)" class="shadow-md hover:bg-gray-600 text-gray-100 bg-gray-400 px-5 py-3 rounded">
                {{ project.label }}
            </Link>
        </div>
    </PageLayout>
</template>

