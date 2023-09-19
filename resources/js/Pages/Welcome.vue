<script setup>
import { Head } from '@inertiajs/vue3';
import WelcomeModal from '@/Components/Modal/WelcomeModal.vue';
import PageLayout from '@/Layouts/PageLayout.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Welcome" />
    <div class="absolute top-0 bg-[url('/img/bg.jpg')] bg-cover min-h-screen w-full"></div>
    <WelcomeModal :show-modal="false"/>
    <PageLayout>

    </PageLayout>
</template>
