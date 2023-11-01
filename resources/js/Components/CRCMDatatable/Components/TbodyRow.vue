<template>
    <tr class="dtBodyRow active:bg-gray-400 sm:h-8 border border-gray-400 rounded" :class="isSelected?'bg-blue-400 hover:brightness-75 duration-300':'hover:bg-gray-300'">
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
