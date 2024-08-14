<script setup>
import Footer from '@/Pages/Footer.vue';
import Body from '@/Pages/Body.vue';
import BackToTop from "@/Components/BackToTop.vue";
import NotifBanner from "@/Components/Modal/Notification/NotifBanner.vue";
const props = defineProps({
    isWideDisplay: {
        type: Boolean,
        default: true,
    }
});
</script>
<template>
    <Body :is-wide-display="props.isWideDisplay">
    <NotifBanner />
    <slot />
    <back-to-top />
    </Body>
</template>
