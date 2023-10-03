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
    <div class="relative flex flex-col" :class="props.isWideDisplay?'mx-0':'resp-container'">
        <slot />
    </div>
</template>
