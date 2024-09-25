<script setup>
const props = defineProps({
    text: String,
    link: String,
})
</script>
<template>
    <li v-if="link" class="flex hover:bg-gray-700 px-3 hover:rounded-sm font-light text-xs sm:text-sm">
        <a :href="link" target="_blank">{{ text }}</a>
    </li>
    <li v-else class="flex text-sm sm:text-lg">
        {{ text }}
    </li>
</template>
