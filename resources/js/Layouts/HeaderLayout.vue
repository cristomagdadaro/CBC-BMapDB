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
        <div class="relative flex flex-col resp-container w-full">
            <!-- Large screen navigation bar -->
            <div class="w-full relative lg:flex hidden">
                <div class="flex justify-between w-full overflow-hidden">
                    <!-- Branding Section -->
                    <Link :href="'/'"
                        class="flex items-center max-w-fit hover:bg-gray-900 hover:bg-opacity-10 active:scale-95 duration-200 p-1 gap-1">
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
