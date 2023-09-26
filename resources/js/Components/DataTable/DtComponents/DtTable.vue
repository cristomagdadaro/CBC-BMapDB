<template>
    <div :class="classes" class="border">
        <table class="w-full border-collapse text-sm">
            <slot />
        </table>
    </div>
</template>
<script setup>
defineProps({
    classes: {
        type: String,
    },
})
</script>
