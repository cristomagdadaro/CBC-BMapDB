<script setup>
import Body from '@/Pages/Body.vue';
import BackToTop from "@/Components/BackToTop.vue";
import NotifBanner from "@/Components/Modal/Notification/NotifBanner.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import {ref} from "vue";
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
