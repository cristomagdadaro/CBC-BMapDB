<template>
    <tr class="dtBodyRow hover:bg-gray-300 active:bg-gray-400 sm:h-8" :class="isSelected?'bg-cbc-yellow-green':''">
        <slot />
    </tr>
</template>
<script setup>
    defineProps({
        isSelected: {
            type: Boolean,
            default: false
        }
    })
</script>
