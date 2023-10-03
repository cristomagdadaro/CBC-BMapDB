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
            activeClass: 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-white focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out',
            inactiveClass: 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-white hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out',
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

};
</script>
<template>
    <li v-if="!sublinks" class="flex items-center hover:bg-cbc-yellow-green duration-400 ease-in-out">
        <Link :href="link" class="px-3 py-1 whitespace-nowrap sm:text-sm text-xs" :class="active?activeClass:inactiveClass"><slot /></Link>
    </li>
    <li v-else class="flex items-center hover:bg-cbc-yellow-green duration-400 ease-in-out" @mouseleave="closeDropdown()">
        <div>
            <div @mouseover="toggleDropdown()" class="flex items-center px-3 py-1 text-white whitespace-nowrap sm:text-sm text-xs">
                <slot name="trigger" />
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
                <ul class="absolute flex flex-col bg-white z-50 mt-2 rounded-md shadow-md p-2 border-[1px] border-gray-100" v-show="showDropdown">
                    <slot name="content" />
                </ul>
            </transition>
        </div>
    </li>
</template>
