<script setup>
const props = defineProps({
    text: String,
    link: String,
})
</script>
<template>
    <li class="flex hover:bg-gray-700 px-3 hover:rounded-sm">
        <a :href="link">{{ text }}</a>
    </li>
</template>
