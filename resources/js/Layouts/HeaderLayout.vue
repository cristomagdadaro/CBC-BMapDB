<script>
import { Link } from "@inertiajs/vue3";
import hamburger from "@/Components/Icons/Hamburger.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import Dropdown from "@/Components/Dropdown.vue";

export default {
    components: {
        Link,
        hamburger,
        DropdownLink,
        Dropdown,
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
</script>
<template>
    <header class="flex items-center justify-between min-w-min w-full bg-cbc-dark-green text-white sticky top-0 z-[49]">
        <div class="relative flex flex-col sm:mx-20 md:mx-40 lg:mx-60 mx-2 w-full">
            <!-- Large screen navigation bar -->
            <div class="w-full relative lg:flex hidden">
                <div class="flex justify-between w-full">
                    <!-- Branding Section -->
                    <Link
                        :href="'/'"
                        class="flex items-center max-w-fit hover:bg-gray-900 hover:bg-opacity-10 active:scale-95 duration-200 p-1 gap-1"
                    >
                        <slot name="icon"></slot>
                        <div class="flex flex-col max-w-fit">
                            <span class="sm:text-sm text-xs whitespace-nowrap tracking-[0.2rem]">
                                <slot name="subtitle"></slot>
                            </span>
                            <span
                                class="sm:text-2xl md:text-xl text-xs whitespace-nowrap"
                            >
                                <slot name="title"></slot>
                            </span>
                        </div>
                    </Link>
                    <!-- Link Tabs Section -->
                    <div class="flex">
                        <slot name="links"></slot>
                        <div class="ml-3 relative">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-cbc-dark-green hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                            Settings
                                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>
                                <template #content>
                                    <!-- Account Management -->
                                    <div class="block px-4 py-2 text-xs text-black">
                                        Manage Account
                                    </div>

                                    <DropdownLink :href="route('profile.show')" class="text-black">
                                        Profile
                                    </DropdownLink>

                                    <div class="border-t border-gray-200" />

                                    <!-- Authentication -->
                                    <form @submit.prevent="logout" class="text-black">
                                        <DropdownLink as="button">
                                            Log Out
                                        </DropdownLink>
                                    </form>
                                </template>
                            </Dropdown>
                        </div>
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
                        @click="toggler()"
                        class="h-7 w-auto block active:scale-110 duration-300"
                    />
                </div>
                <!-- Link Tabs Section -->
                <div :class="showMenu ? 'block' : 'hidden'">
                    <!-- Link Tabs Section -->
                    <div class="lg:flex block">
                        <slot name="links"></slot>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>
