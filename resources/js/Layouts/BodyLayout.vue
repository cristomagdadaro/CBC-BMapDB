<script setup>
import {defineProps} from "vue";
const props = defineProps({
    isWideDisplay: {
        type: Boolean,
        default: false
    }
});
</script>
<template>
    <div
        class="relative flex flex-col min-h-screen h-full max-h-[100vh] overflow-hidden"
        :class="isWideDisplay ? 'mx-0' : 'resp-container'"
    >
        <div>
            <slot name="header"></slot>
        </div>
        <div>
            <slot name="breadcrumbs"></slot>
        </div>
        <div class="pt-3 h-full w-full min-h-full justify-center overflow-y-auto">
            <slot></slot>
        </div>
    </div>
</template>
