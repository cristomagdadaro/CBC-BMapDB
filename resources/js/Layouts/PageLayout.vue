<script>
import Body from '@/Pages/Body.vue'
import BackToTop from "@/Components/BackToTop.vue"
import NotifBanner from "@/Components/Modal/Notification/NotifBanner.vue"
import AiChat from "@/Pages/OpenAi/AiChat/AiChat.vue"

export default {
    name: "Layout",

    components: {
        Body,
        BackToTop,
        NotifBanner,
        AiChat
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

        <!-- AI Chat -->
        <AiChat />

        <slot />

        <BackToTop />
    </Body>
</template>