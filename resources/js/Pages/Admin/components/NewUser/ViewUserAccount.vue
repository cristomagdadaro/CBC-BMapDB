<script setup>

import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
</script>

<template>
    <Head title="Breeders' Map View" />
    <app-layout>
        <pre>
            {{ $page.props.view}}
        </pre>
    </app-layout>
</template>

<style scoped>

</style>
