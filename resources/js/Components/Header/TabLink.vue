<script>
import { Link } from '@inertiajs/vue3';
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
        isScrolled: {
            type: Boolean,
            default: true,
        },
        mobile: {
            type: Boolean,
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
    },
};
</script>
<template>
    <!-- Mobile link -->
    <template v-if="mobile">
        <div v-if="!sublinks">
            <Link :href="link" class="block px-4 py-3 rounded-lg text-gray-700 hover:bg-pin-green-light hover:text-pin-green font-medium transition-colors">
                <slot />
            </Link>
        </div>
        <div v-else>
            <button @click="toggleDropdown" class="w-full text-left px-4 py-3 rounded-lg text-gray-700 hover:bg-pin-green-light hover:text-pin-green font-medium transition-colors flex items-center justify-between">
                <slot name="trigger" />
                <svg :class="['w-4 h-4 transition-transform', showDropdown ? 'rotate-180' : '']" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div v-show="showDropdown" class="pl-4 space-y-1">
                <slot name="content" />
            </div>
        </div>
    </template>

    <!-- Desktop link -->
    <template v-else>
        <div v-if="!sublinks" class="flex items-center">
            <Link
                v-if="!externalLink"
                :href="link"
                :class="[
                    'px-4 py-2 rounded-lg text-sm font-medium transition-all focus-ring',
                    isScrolled
                        ? (active ? 'text-pin-green bg-pin-green-light' : 'text-gray-700 hover:text-pin-green hover:bg-pin-green-light')
                        : (active ? 'text-white bg-white/15' : 'text-white/90 hover:text-white hover:bg-white/10')
                ]"
            >
                <slot />
            </Link>
            <a
                v-else
                :href="link"
                target="_blank"
                :class="[
                    'px-4 py-2 rounded-lg text-sm font-medium transition-all focus-ring',
                    isScrolled
                        ? 'text-gray-700 hover:text-pin-green hover:bg-pin-green-light'
                        : 'text-white/90 hover:text-white hover:bg-white/10'
                ]"
            >
                <slot />
            </a>
        </div>
        <div v-else class="relative" @mouseenter="showDropdown = true" @mouseleave="closeDropdown()">
            <!-- Full Screen Overlay -->
            <div v-show="showDropdown" class="fixed w-full h-full top-0 left-0 z-[1]" @click="closeDropdown()"></div>

            <button
                @click="toggleDropdown()"
                :class="[
                    'relative z-[2] px-4 py-2 rounded-lg text-sm font-medium transition-all flex items-center gap-1.5 focus-ring',
                    isScrolled
                        ? (active ? 'text-pin-green bg-pin-green-light' : 'text-gray-700 hover:text-pin-green hover:bg-pin-green-light')
                        : (active ? 'text-white bg-white/15' : 'text-white/90 hover:text-white hover:bg-white/10')
                ]"
            >
                <slot name="trigger" />
                <svg :class="['w-4 h-4 transition-transform', showDropdown ? 'rotate-180' : '']" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0 translate-y-1"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 translate-y-1"
            >
                <div
                    v-show="showDropdown"
                    class="absolute top-full left-0 mt-2 w-72 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-[46]"
                    @mouseleave="closeDropdown()"
                >
                    <slot name="content" />
                </div>
            </transition>
        </div>
    </template>
</template>
