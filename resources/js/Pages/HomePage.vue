<script setup>
import PageLayout from '@/Layouts/PageLayout.vue';
import WelcomeModal from '@/Components/Modal/WelcomeModal.vue';
import { Head } from '@inertiajs/vue3';
import UnderDevelop from "@/Components/Modal/UnderDevelop.vue";
</script>
<template>
    <Head title="Home" />
    <WelcomeModal />
    <page-layout :is-wide-display="true">
        <under-develop />
    </page-layout>
</template>
