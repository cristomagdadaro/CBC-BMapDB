<script setup>
import BodyLayout from '@/Layouts/BodyLayout.vue';
import BreadCrumb from "@/Components/BreadCrumb.vue";
import Header from "@/Pages/Header.vue";
import GreenWaves from "@/Components/GreenWaves.vue";
import Footer from "@/Pages/Footer.vue";
const props = defineProps({
    isWideDisplay: {
        type: Boolean,
        default: false
    }
});
</script>
<template>
    <green-waves />
    <BodyLayout :is-wide-display="props.isWideDisplay">
        <template #header>
            <Header :can-login="true" :can-register="true" />
        </template>
        <template #breadcrumbs>
            <BreadCrumb />
        </template>
        <main class="bg-transparent resp-container">
            <slot />
        </main>
    </BodyLayout>
    <Footer class="hidden" />
</template>
