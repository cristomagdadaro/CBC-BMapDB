<script setup>
import Header from '@/Pages/Header.vue';
import Footer from '@/Pages/Footer.vue';
import Body from '@/Pages/Body.vue';
import NotifBanner from "@/Components/Modal/Notification/NotifBanner.vue";
import BackToTop from "@/Components/BackToTop.vue";
const props = defineProps({
    isWideDisplay: {
        type: Boolean,
        default: true,
    }
});
</script>
<template>
    <Header :can-login="true" :can-register="true" />
    <NotifBanner />

    <Body :is-wide-display="props.isWideDisplay">
        <slot />
    </Body>
    <back-to-top />
<!--    <Footer />-->
</template>
