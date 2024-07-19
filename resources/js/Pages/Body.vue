<script setup>
import BodyLayout from '@/Layouts/BodyLayout.vue';
import BreadCrumb from "@/Components/BreadCrumb.vue";
const props = defineProps({
    isWideDisplay: {
        type: Boolean,
        default: false
    }
});
</script>
<template>
    <BodyLayout :is-wide-display="props.isWideDisplay">
        <bread-crumb />
        <main class="min-h-screen bg-transparent">
            <slot />
        </main>
    </BodyLayout>
</template>
