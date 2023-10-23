<template>
    <div class="flex items-center gap-0.5 border-black bg-white rounded">
        <input class="border-0 py-1.5 rounded-l" type="text" id="dtSearch" placeholder="Search" @keyup.capture.enter="$emit('searchString', $event.target.value)" />
        <button class="bg-transparent rounded-r px-2 hover:bg-gray-300 active:bg-gray-400 h-full" @click="$emit('searchString', document.getElementById('dtSearch').value )"><search-icon class="h-5 w-5" /></button>
    </div>
</template>
<script setup>
import SearchIcon from "@/Components/Icons/SearchIcon.vue";
</script>
