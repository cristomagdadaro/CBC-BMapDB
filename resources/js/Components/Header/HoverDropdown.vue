<script setup>

import { ref } from 'vue';
import tablink from '@/Components/Header/TabLink.vue';

const showDropdown = ref(false);

const toggleDropdown = () => {
    showDropdown.value = !showDropdown.value;
}

const closeDropdown = () => {
    showDropdown.value = false;
}
</script>

<template>
    <div class="relative" @mouseleave="closeDropdown()">
        <div>
            <div @mouseover="toggleDropdown()">
                Options
            </div>
            <!-- Full Screen Dropdown Overlay -->
            <div v-show="showDropdown" class="fixed inset-0 z-40" @click="closeDropdown()" />

            <transition
                enter-active-class="transition ease-out duration-300"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95">
                <ul class="absolute flex flex-col bg-gray-800 z-50 mt-2 rounded-md shadow-md p-2" v-show="showDropdown">
                    <tablink link="/" class="rounded-sm">Sample 1</tablink>
                    <tablink link="/" class="rounded-sm">Sample 2</tablink>
                    <tablink link="/" class="rounded-sm">Sample 3</tablink>
                </ul>
            </transition>
        </div>
    </div>
</template>
