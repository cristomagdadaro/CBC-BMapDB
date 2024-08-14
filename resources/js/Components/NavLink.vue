<script setup>
import {computed, ref} from 'vue';
import { Link } from '@inertiajs/vue3';
import TransitionContainer from "@/Components/CustomDropdown/Components/TransitionContainer.vue";

const props = defineProps({
    href: String,
    active: Boolean,
});

const showSubLinks = ref(false);

const classes = computed(() => {
    return props.active
        ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
        : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-300 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out';
});
</script>

<template>
    <div class="flex flex-col relative">
        <Link :href="href" :class="classes" @mouseenter="showSubLinks = true" @click="showSubLinks = !showSubLinks" >
            <slot />
            <svg v-if="$slots.subLinks" class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
        </Link>
        <transition-container v-show="showSubLinks">
            <div class="flex flex-col px-2 pl-5 gap-2">
                <slot name="subLinks" />
            </div>
        </transition-container>
    </div>
</template>
