<script>
import { Link } from '@inertiajs/vue3';
import hamburger from '@/Components/Icons/Hamburger.vue';

export default {
    components: {
        Link,
        hamburger,
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
    <div class="flex items-center backdrop-blur-sm justify-between px-5 min-w-min fixed w-full">
        <!-- Large screen navigation bar -->
        <div class="w-full relative">
            <div class="lg:flex hidden justify-between w-full">
                <!-- Branding Section -->
                <Link :href="'/'" class="flex items-center max-w-fit hover:bg-gray-900 hover:bg-opacity-10 active:scale-95 duration-200 p-1 gap-1">
                    <slot name="icon"></slot>
                    <div class="flex flex-col max-w-fit">
                        <span class="sm:text-sm text-xs whitespace-nowrap">
                            <slot name="subtitle"></slot>
                        </span>
                        <span class="sm:text-2xl md:text-xl text-xs font-bold tracking-wide whitespace-nowrap">
                            <slot name="title"></slot>
                        </span>
                    </div>
                </Link>
                <!-- Link Tabs Section -->
                <div class="flex">
                    <slot name="links"></slot>
                </div>
            </div>
            <div class="relative top-0 flex h-full w-full backdrop-blur-sm">
                <slot name="modal" />
            </div>
        </div>
        <!-- Small screen navigation bar -->
       <div class="flex-col w-full lg:hidden">
            <div class="flex justify-between items-center">
                <!-- Branding Section -->
                <a href="/" class="flex items-center max-w-fit hover:bg-gray-900 hover:bg-opacity-10 active:scale-95 duration-200 p-2 gap-1">
                    <slot name="icon"></slot>
                    <div class="flex flex-col max-w-fit">
                        <span class="sm:text-sm text-xs whitespace-nowrap">
                            <slot name="suptitle"></slot>
                        </span>
                        <span class="sm:text-2xl md:text-xl text-lg font-bold tracking-wide whitespace-nowrap">
                            <slot name="title"></slot>
                        </span>
                    </div>
                </a>
                <hamburger @click="toggler()" class="h-7 w-auto block lg:hidden active:scale-110 duration-300" />
            </div>
            <!-- Link Tabs Section -->
            <div :class="showMenu?'block backdrop-blur-sm':'hidden'">
                <!-- Link Tabs Section -->
                <div class="sm:flex">
                    <slot name="links"></slot>
                </div>
            </div>
        </div>
    </div>
</template>
