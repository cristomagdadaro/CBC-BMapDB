<script>
import { Link } from '@inertiajs/vue3';
export default {
    components: {
        Link,
    },
    props: {
        link: {
            type: String,
            required: true,
            default: '#',
        },
        sublinks: {
            type: Boolean,
            required: false,
            default: false,
        },
    },
    data() {
        return {
            showDropdown: false,
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
        <Link :href="link" class="px-3 py-1 whitespace-nowrap sm:text-sm text-xs"><slot /></Link>
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
