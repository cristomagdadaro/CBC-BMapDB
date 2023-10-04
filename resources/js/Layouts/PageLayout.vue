<script setup>
import Header from '@/Pages/Header.vue';
import Footer from '@/Pages/Footer.vue';
import Body from '@/Pages/Body.vue';
import NotifBanner from "@/Components/Modal/NotifBanner.vue";
const props = defineProps({
    isWideDisplay: {
        type: Boolean,
        default: false
    }
});
</script>
<template>
    <div class="flex flex-col relative">
        <Header :can-login="true" :can-register="true" />
        <NotifBanner />
        <Body :is-wide-display="props.isWideDisplay">
            <slot />
        </Body>
        <Footer />
    </div>
</template>
