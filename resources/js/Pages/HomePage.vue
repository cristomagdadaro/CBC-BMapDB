<script setup>
import PageLayout from '@/Layouts/PageLayout.vue';
import WelcomeModal from '@/Components/Modal/WelcomeModal.vue';
import { Head } from '@inertiajs/vue3';
</script>
<template>
    <Head title="Home" />
    <div class="absolute top-0 bg-[url('/img/bg.jpg')] bg-cover min-h-screen w-full"></div>
    <WelcomeModal />
    <PageLayout>
        <h1>HOME PAGE</h1>
    </PageLayout>
</template>