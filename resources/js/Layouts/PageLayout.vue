<script>
import Body from '@/Pages/Body.vue'
import BackToTop from "@/Components/BackToTop.vue"
import NotifBanner from "@/Components/Modal/Notification/NotifBanner.vue"
export default {
    name: "Layout",

    components: {
        Body,
        BackToTop,
        NotifBanner
    },

    props: {
        isWideDisplay: {
            type: Boolean,
            default: true
        }
    }
}
</script>

<template>
    <Body :is-wide-display="isWideDisplay">
        <NotifBanner />

        <!-- AI Chat widget removed in favor of central SproutAi script in app.blade.php -->

        <slot />

        <BackToTop />
    </Body>
</template>