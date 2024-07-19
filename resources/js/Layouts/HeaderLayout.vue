<script>
import { Link } from "@inertiajs/vue3";
import hamburger from "@/Components/Icons/Hamburger.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import Dropdown from "@/Components/Dropdown.vue";
import {computed} from "vue";
import TransitionContainer from "@/Components/CustomDropdown/Components/TransitionContainer.vue";

export default {
    components: {
        TransitionContainer,
        Link,
        hamburger,
        DropdownLink,
        Dropdown,
    },
    props: {
        active: Boolean,
    },
    data() {
        return {
            showMenu: false,
        };
    },
    methods: {
        toggler() {
            this.showMenu = !this.showMenu;
        },
    },
};

const classes = computed(() => {
    return this.active
        ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
        : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out';
});
</script>
<template>
    <header class="flex items-center justify-between min-w-min w-full bg-cbc-dark-green text-white sticky top-0 z-[49] p-2">
        <div class="relative flex flex-col resp-container w-full">
            <!-- Large screen navigation bar -->
            <div class="w-full relative lg:flex hidden">
                <div class="flex justify-between w-full overflow-hidden">
                    <!-- Branding Section -->
                    <Link :href="'/'"
                        class="flex items-center max-w-fit hover:bg-gray-900 hover:bg-opacity-10 active:scale-95 duration-200 p-2 gap-1 rounded">
                        <slot name="icon"></slot>
                        <div class="flex flex-col max-w-fit">
                            <span class="sm:text-sm text-xs whitespace-nowrap tracking-[0.2rem] leading-tight">
                                <slot name="subtitle"></slot>
                            </span>
                            <span class="sm:text-2xl md:text-xl text-xs whitespace-nowrap leading-[1]">
                                <slot name="title"></slot>
                            </span>
                        </div>
                    </Link>
                    <!-- Link Tabs Section -->
                    <div class="flex">
                        <slot name="links"></slot>
                    </div>
                </div>
            </div>
            <!-- Small screen navigation bar -->
            <div class="w-full lg:hidden">
                <div class="flex justify-between items-center">
                    <!-- Branding Section -->
                    <Link
                        :href="'/'"
                        class="flex items-center max-w-fit hover:bg-gray-900 hover:bg-opacity-10 active:scale-95 duration-200 p-1 gap-1"
                    >
                        <slot name="icon"></slot>
                        <div class="flex flex-col max-w-fit">
                            <span class="sm:text-sm text-xs whitespace-nowrap">
                                <slot name="subtitle"></slot>
                            </span>
                            <span
                                class="sm:text-2xl md:text-xl text-xs font-bold tracking-wide whitespace-nowrap"
                            >
                                <slot name="title"></slot>
                            </span>
                        </div>
                    </Link>
                    <hamburger
                        :showMenu="showMenu"
                        @click="toggler()"
                        class="h-7 w-auto block active:scale-110 duration-300"
                    />
                </div>
                <!-- Link Tabs Section -->
                <transition
                    enter-active-class="transition ease-out duration-500"
                    enter-from-class="transform opacity-0 scale-95"
                    enter-to-class="transform opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-200"
                    leave-from-class="transform opacity-100 scale-100"
                    leave-to-class="transform opacity-0 scale-95"
                >
                    <div v-show="showMenu">
                        <!-- Link Tabs Section -->
                        <div class="lg:flex block">
                            <slot name="links"></slot>
                        </div>
                    </div>
                </transition>
            </div>
        </div>
    </header>
</template>
