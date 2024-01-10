<script setup>
import { ref } from "vue";
import Hamburger from "@/Components/Icons/Hamburger.vue";

const isOpen = ref(false);
</script>

<template>
    <div class="flex z-50">
        <div
            :class="isOpen ? 'w-12' : 'w-44'"
            class="flex-col h-screen p-3 duration-300 bg-gray-500 shadow hidden sm:flex">
            <div class="space-y-3">
                <div class="flex items-center text-gray-100 justify-between">
                    <h2 v-show="!isOpen">Applications</h2>
                    <button @click="isOpen = !isOpen">
                        <hamburger />
                    </button>
                </div>
                <div class="flex flex-col">
                    <slot name="options" />
                </div>
            </div>
        </div>
        <div class="container mx-auto overflow-x-scroll">
            <slot name="content" />
        </div>
    </div>
</template>

<style scoped>

</style>
