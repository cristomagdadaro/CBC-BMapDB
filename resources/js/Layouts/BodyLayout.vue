<script setup>
const props = defineProps({
    isWideDisplay: {
        type: Boolean,
        default: false
    }
});
</script>
<template>
    <div class="min-h-screen h-full">
        <div
            class="relative flex flex-col"
            :class="isWideDisplay ? 'mx-0' : 'resp-container'"
        >
            <div>
                <slot name="header"></slot>
            </div>
            <div class="h-full w-full min-h-full justify-center">
                <slot />
            </div>
        </div>
    </div>
</template>
