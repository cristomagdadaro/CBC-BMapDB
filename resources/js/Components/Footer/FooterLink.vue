<script setup>
const props = defineProps({
    text: String,
    link: String,
})
</script>
<template>
    <li v-if="link" class="flex hover:bg-gray-700 px-3 hover:rounded-sm text-xs">
        <a :href="link">{{ text }}</a>
    </li>
    <li v-else class="flex text-sm">
        {{ text }}
    </li>
</template>
