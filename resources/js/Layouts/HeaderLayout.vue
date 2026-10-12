<script>
import { Link } from "@inertiajs/vue3";
import hamburger from "@/Components/Icons/Hamburger.vue";

export default {
    components: {
        Link,
        hamburger,
    },
    props: {
        active: Boolean,
    },
    data() {
        return {
            showMenu: false,
            isScrolled: false,
        };
    },
    mounted() {
        this.handleScroll();
        window.addEventListener("scroll", this.handleScroll, { passive: true });
    },
    beforeUnmount() {
        window.removeEventListener("scroll", this.handleScroll);
    },
    methods: {
        toggler() {
            this.showMenu = !this.showMenu;
        },
        handleScroll() {
            this.isScrolled = window.scrollY > 50;
        },
    },
};
</script>

<template>
    <header
        :class="[
            'fixed top-0 left-0 right-0 z-50 transition-all duration-300',
            isScrolled
                ? 'bg-white/90 dark:bg-gray-900/90 backdrop-blur-md shadow-sm border-b border-gray-200/50 dark:border-gray-800/60 py-2.5'
                : 'bg-transparent py-4',
        ]"
    >
        <nav class="section-padding">
            <div class="container-custom flex items-center justify-between">
                <!-- Logo / Branding -->
                <Link
                    :href="'/'"
                    class="flex items-center gap-3 group focus:outline-none focus:ring-2 focus:ring-emerald-500/50 rounded-lg"
                >
                    <slot name="icon"></slot>
                    <div class="hidden sm:block leading-tight">
                        <p
                            :class="[
                                'text-xs font-medium transition-colors uppercase tracking-wider',
                                isScrolled
                                    ? 'text-gray-500 dark:text-gray-400'
                                    : 'text-white/80 dark:text-gray-300',
                            ]"
                        >
                            <slot name="subtitle"></slot>
                        </p>
                        <p
                            :class="[
                                'text-lg font-bold transition-colors',
                                isScrolled
                                    ? 'text-emerald-600 dark:text-emerald-400'
                                    : 'text-white dark:text-gray-100',
                            ]"
                        >
                            <slot name="title"></slot>
                        </p>
                    </div>
                </Link>

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center gap-1">
                    <slot name="links" :is-scrolled="isScrolled"></slot>
                </div>

                <!-- Mobile Menu Button -->
                <button
                    class="lg:hidden p-2 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
                    :class="[
                        isScrolled
                            ? 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800'
                            : 'text-white dark:text-gray-100 hover:bg-white/10 dark:hover:bg-gray-800/50',
                    ]"
                    @click="toggler()"
                    :aria-expanded="showMenu"
                    :aria-label="showMenu ? 'Close menu' : 'Open menu'"
                >
                    <svg
                        v-if="!showMenu"
                        class="w-6 h-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                    </svg>
                    <svg
                        v-else
                        class="w-6 h-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
        </nav>

        <!-- Mobile Navigation Menu -->
        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 -translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-2"
        >
            <div
                v-show="showMenu"
                class="lg:hidden bg-white/95 dark:bg-gray-900/95 backdrop-blur-lg border-t border-gray-100 dark:border-gray-800 shadow-xl"
            >
                <div
                    class="section-padding py-4 space-y-1 text-gray-800 dark:text-gray-200"
                >
                    <slot name="mobile-links"></slot>
                </div>
            </div>
        </transition>
    </header>
</template>
