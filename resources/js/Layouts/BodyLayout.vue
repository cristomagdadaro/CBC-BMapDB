<script setup>
import {defineProps} from "vue";
import Footer from "@/Pages/Footer.vue";
const props = defineProps({
    isWideDisplay: {
        type: Boolean,
        default: false
    }
});
</script>
<template>
    <div class="min-h-screen h-full max-h-[100vh]">
        <div
            class="relative flex flex-col"
            :class="isWideDisplay ? 'mx-0' : 'resp-container'"
        >
            <div>
                <slot name="header"></slot>
            </div>
            <div class="bg-cbc-dark-green">
                <div class="flex flex-row resp-container">
                    <slot name="breadcrumbs"></slot>
                </div>
            </div>
            <div class="h-full w-full min-h-full justify-center">
                <slot />
            </div>
        </div>
        <Footer />
    </div>
</template>
