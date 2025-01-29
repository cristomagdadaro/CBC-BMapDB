<script>
import { Link } from '@inertiajs/vue3';
import {computed} from "vue";
export default {
    components: {
        Link,
    },
    props: {
        link: {
            type: String,
            required: false,
            default: '#',
        },
        externalLink: {
            type: Boolean,
            required: false,
            default: false,
        },
        sublinks: {
            type: Boolean,
            required: false,
            default: false,
        },
        active: {
            type: Boolean,
            required: false,
            default: false,
        },
    },
    data() {
        return {
            showDropdown: false,
            activeClass: 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out',
            inactiveClass: 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out',
        };
    },
    methods: {
        toggleDropdown() {
            this.showDropdown = !this.showDropdown;
        },
        closeDropdown() {
            this.showDropdown = false;
        },
        /* function that would determine if the current tab is active*/
    },
    mounted() {
        if(this.sublinks)
            this.$refs.hoverDropdown.addEventListener('mouseover', this.toggleDropdown)
    }
};
</script>
<template>
    <div v-if="!sublinks" class="flex text-gray-100 items-center hover:bg-cbc-yellow-green duration-400 ease-in-out">
        <Link v-if="!externalLink" :href="link" class="px-3 py-1 whitespace-nowrap text-normal" :class="active?activeClass:inactiveClass"><slot /></Link>
        <a v-else :href="link" target="_blank" class="px-3 py-1 whitespace-nowrap text-normal" :class="active?activeClass:inactiveClass"><slot /></a>
    </div>
    <div v-else class="flex items-center hover:bg-cbc-yellow-green duration-400 ease-in-out">
        <div @mouseleave="closeDropdown()">
            <!-- Full Screen Dropdown Overlay -->
            <div v-show="showDropdown" class="fixed w-full h-full top-0 left-0 z-[1]" @click="closeDropdown()"></div>

            <div @click="toggleDropdown()" ref="hoverDropdown" class="z-[100]">
                <Link @clic.prevent="null" :href="link" class="px-3 py-1 text-gray-100 whitespace-nowrap text-normal" :class="active?activeClass:inactiveClass">
                    <slot name="trigger" />
                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </Link>
            </div>

            <transition
                enter-active-class="transition ease-out duration-300"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95">
                <div class="absolute flex flex-col bg-gray-100 z-[46] mt-2 shadow-md p-3 text-subtitle" v-show="showDropdown" @mouseleave="closeDropdown()">
                    <slot name="content" />
                </div>
            </transition>
        </div>
    </div>
</template>
