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
    <div v-else class="flex items-center hover:bg-cbc-yellow-green duration-400 ease-in-out" @mouseleave="closeDropdown()">
        <div>
            <!-- Full Screen Dropdown Overlay -->
            <div v-show="showDropdown" class="fixed z-[1]" @click="closeDropdown()" />

            <div @click="toggleDropdown()" ref="hoverDropdown" class="z-[100]">
                <Link :href="link" class="px-3 py-1 text-gray-100 whitespace-nowrap text-normal" :class="active?activeClass:inactiveClass"><slot name="trigger" /></Link>
            </div>

            <transition
                enter-active-class="transition ease-out duration-300"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95">
                <div class="absolute flex flex-col bg-gray-100 z-[46] mt-2 shadow-md p-3 text-subtitle" v-show="showDropdown">
                    <slot name="content" />
                </div>
            </transition>
        </div>
    </div>
</template>
